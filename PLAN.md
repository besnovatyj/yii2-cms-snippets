# Yii2-CMS Snippets — план реализации

Система **сниппетов** для WYSIWYG-редактора: сохранённые куски HTML/текста, которые
контент-менеджер быстро вставляет в контент из тулбара редактора (поиск + категории +
превью). Отдельный модуль + движко-независимое npm-ядро + тонкая связка с движком —
зеркалит уже отработанные в проекте паттерны файлового менеджера.

## Статус

- [x] Шаг 1 — контракт `SnippetProvider` + DTO (yii2-cms-contracts)
- [x] Шаг 2 — модуль yii2-cms-snippets (домен, админка, API-tree)
- [x] Шаг 3 — npm-ядро `@besnovatyj/snippets-core` (пикер)
- [x] Шаг 4 — связка в yii2-cms-jodit (плагин + JoditWidget + esbuild-флаг); иконка кнопки — своя SVG (встроенное имя 'paste' не резолвилось → пустая некликабельная кнопка)
- [x] Шаг 5 — опция `enableSnippets` в фасаде (EditorOptions + EditorWidget + JoditEditorAdapter)
- [~] Шаг 6 — СНЯТ. Задача сниппетов одна: отдавать предформатированные куски темы. Шорткоды
  самодостаточны и работают на рендере сами; интеграции с ними не требуется. Тело сниппета
  хранится и вставляется сырым — существующий шорткод-конвейер поля-потребителя разворачивает
  любые шорткоды внутри автоматически. Шорткод `[snippet]` НЕ делаем (осознанно).

Фича функционально завершена (Шаги 1–5). Для рабочего пикера нужен установленный модуль
(иначе API `/Snippets/backend/api/tree` → 404, пикер покажет ошибку):
modman install Snippets + recompile + reload php-fpm.

## Решения (зафиксированы)

- **Источник:** модуль + БД + контракт-провайдер. Сниппеты правит контент-менеджер в
  админке; другие модули могут поставлять свои через контракт `SnippetProvider`.
- **UI пикера:** rich-попап (поиск + категории + превью) на отдельном движко-независимом
  npm-ядре `@besnovatyj/snippets-core` — полный аналог `@besnovatyj/filemanager-core`,
  переиспользуемый CKEditor'ом.

## Сниппет ≠ шорткод

| | Шорткод (`%homeUrl%`, widget) | Сниппет |
|---|---|---|
| Когда | **render-time** на фронте (`ShortcodeTextResolver`) | **edit-time** — HTML вставляется в контент буквально |
| Хранит | пара `ключ → замена` | категория, заголовок, HTML-тело, превью, порядок |
| В контенте | плейсхолдер `%name%` | готовый HTML |

**Ортогональны и компонуются:** тело сниппета может содержать шорткоды
(`<div>%phone%</div>`) — edit-time шаблон с render-time значениями. Тело сниппета на
рендере фронта проходит существующий `ShortcodeTextResolver` бесплатно.

## Раскладка по репозиториям (всё в `vendor`, `--prefer-source`)

```
app/vendor/besnovatyj/yii2-cms-contracts     — контракт SnippetProvider + DTO (шаг 1)
app/vendor/besnovatyj/yii2-cms-snippets      — модуль: домен, админка, API (шаг 2)  ← ЭТОТ ПАКЕТ
app/packages/npm/snippets-core               — движко-независимое npm-ядро пикера (шаг 3)
app/vendor/besnovatyj/yii2-cms-jodit         — тонкая связка + esbuild SNIPPETS_CORE_LOCAL (шаг 4)
app/vendor/besnovatyj/yii2-cms-editor        — опция enableSnippets в фасаде (шаг 5)
```

## Порядок реализации

### Шаг 1 — Контракт (`yii2-cms-contracts`)
Зеркало `MenuTargetProvider`. Нейтральные DTO, без проектной специфики.

- `src/snippet/SnippetProvider.php` — `interface { snippetGroups(): SnippetGroup[] }`.
  Реализуется классом Yii-модуля-провайдера; агрегатор находит их `instanceof`-ом (DIP).
- `src/snippet/SnippetGroup.php` — `readonly DTO { string $id, string $label, int $sort, Snippet[] $items }`.
- `src/snippet/Snippet.php` — `readonly DTO { string $id, string $title, string $html,
  ?string $preview, string[] $keywords }`.

### Шаг 2 — Модуль `yii2-cms-snippets` (ЭТОТ ПАКЕТ)
Скелет по образцу `yii2-cms-shortcode` (CmsModule + config-plugin + adminMenu + migrations).

- `entities/Snippet.php`, `entities/SnippetGroup.php` — AR; таблицы `snippet_snippets`,
  `snippet_groups`; `sort_order` на обеих; связь group→items.
- `migrations/mXXXXXX_create_snippet_tables.php` — `BaseMigration`, install-only.
- `repositories/SnippetRepository.php`, `NotFoundException.php`.
- `services/manage/SnippetManageService.php`, `services/manage/GroupManageService.php` — CRUD.
- `services/SnippetCatalog.php` — агрегирует ВСЕ `SnippetProvider` (по модулям, как модуль
  меню), собирает единое дерево для API.
- `providers/DbSnippetProvider.php` — реализует контракт из БД (базовый провайдер модуля).
- `forms/backend/SnippetForm.php`, `forms/search/SnippetSearch.php` — на `BaseForm`.
- `controllers/backend/DefaultController.php` — CRUD-админка; **тело сниппета правится
  фасадом `EditorWidget`** (сниппет редактируется тем же редактором, что и контент).
- `controllers/backend/ApiController.php` — `actionTree(): JSON` дерево сниппетов для пикера
  (CSRF/ajax-only). Формат ответа описать TS-интерфейсом в ядре.
- `config/`: `config.php`, `common.php`, `adminMenu.php`; `composer.json` c
  `extra.bescms.kind=module`, `moduleClass`, `moduleId`, `config-plugin`.

### Шаг 3 — npm-ядро `@besnovatyj/snippets-core` (`app/packages/npm/snippets-core`)
Движко-независимое, чистый DOM, без зависимости от редактора. Аналог `filemanager-core`.

- Публичный API: `createSnippetPicker(config)` → рантайм с `open()`, коллбэк
  `onSelect(html: string)`, `onClose()`.
- Внутри: `HttpClient` → fetch дерева по `snippetsUrl`; UI: список категорий, поиск по
  `title`/`keywords`, превью (`preview` или безопасный рендер `html`); выбор → `onSelect`.
- TS-интерфейсы ответа API (`SnippetTreeDto`, `SnippetGroupDto`, `SnippetDto`).
- `esbuild.js` собирает `dist/standalone.js` (+ вшитый CSS) — как у `filemanager-core`.

### Шаг 4 — Связка в `yii2-cms-jodit`
Тонкая, как `assets/plugins/fileManager.ts` — только байндинг, домена нет.

- `assets/plugins/snippets.ts` — `createSnippetsControl(config)` → `IControlType`:
  `name: 'snippets'`, ленивое создание рантайма ядра, вставка `editor.s.insertHTML(html)`.
- `assets/jodit-widget.ts` — регистрация `controls.snippets` при наличии `snippets.snippetsUrl`.
- `JoditWidget.php` — свойства `$enableSnippets`, `$snippetsUrl`; кнопка `snippets` в
  `DEFAULT_BUTTONS` (группа `c_insert`), рекурсивный `stripControl` при выключении.
- `esbuild.js` — флаг `SNIPPETS_CORE_LOCAL`, алиасящий `@besnovatyj/snippets-core` на
  `../../../packages/npm/snippets-core/dist/standalone.js` (как `FM_CORE_LOCAL`).
- Ребилд бандла.

### Шаг 5 — Фасад `yii2-cms-editor`
Сниппеты становятся first-class возможностью редактора, а не фичей Jodit.

- `EditorOptions.php` — `+ ?bool $enableSnippets`.
- `JoditEditorAdapter.php` — пробрасывает опцию (пропускать при `null`).

### Шаг 6 — Интеграция шорткодов
Тело сниппета на рендере фронта проходит существующий `ShortcodeTextResolver` — композиция
edit-time шаблон + render-time значения. Проверить точку рендера контента.

## После правок

- `contracts`/`editor`/`jodit` уехали в GitHub → правки уже под `--prefer-source`.
- Собрать ядро (`node esbuild.js` в `snippets-core`), затем Jodit-бандл с `SNIPPETS_CORE_LOCAL=1`.
- `composer dump-autoload` + modman recompile + install модуля (миграции под www-data).
- Пакеты в прод — через push+tag+composer update.

## Фазирование (внутри выбранного полного варианта)

1. Контракт + модуль (БД, админка, API-tree) + `DbSnippetProvider` + агрегатор.
2. npm-ядро с поиском/категориями/превью; Jodit-плагин на него.
3. Опция в фасаде; (позже) связка для CKEditor5.

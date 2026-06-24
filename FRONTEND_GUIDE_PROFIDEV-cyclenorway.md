# Максимальний гайд для Frontend-розробника теми profi.dev

> **Оновлено: mayo 2026 — ориєнтація на Custom Elements, Gutenberg та Pattern Matching** 🎯

---

## 📁 1. Архітектура теми

### 1.1 Основні файли та їхні ролі

#### `_constants.scss` — Сховище констант
Це **головний файл змінних** теми, який забезпечує глобальну стандартизацію:
- **Розміри шрифтів**: `--theme-fs-h1`, `--theme-fs-h2`, ... `--theme-fs-xs` (для десктопу)
- **Мобільні розміри**: `--theme-fs-mb-h1`, `--theme-fs-mb-h2`, ... (для мобільних)
- **Висота рядків**: `--theme-lh-xl`, `--theme-lh-lg`, `--theme-lh-md`, `--theme-lh-sm`, `--theme-lh-xs`
- **Закруглення**: `--theme-br-lg`, `--theme-br-md`, `--theme-br-sm`, `--theme-br-xs`
- **Розміри контейнерів**: `--theme-container-width`, `--theme-container-padding`
- **Переходи**: `--theme-transition`, `--theme-transition-duration`
- **Кольори**: `--theme-border-color`, `--theme-bg-color`
- **SVG-іконки**: `--theme-icon-chevron`, `--theme-icon-arrow`, `--theme-icon-menu`, тощо (закодовані як data URIs)

> **🚫 КРИТИЧНЕ**: усі числові значення конвертуються в `rem` через міксину `toRem()`. Жодних hardcoded значень типу `20px` або `14px` просто так у стилях!

#### `_mixins.scss` — Утилітарні функції
Утилітарний файл з функціями Sass:
- **Функція `breakpoint()`** — для адаптивних стилів (mobile-first)
- **Функція `toRem()`** — конвертує px в rem (16px = 1rem)
- **Функція `svg-encode()`** — кодує SVG для використання як фон у CSS
- **Функція `strip-units()`** — видаляє одиниці виміру з числа
- **Міксина `initFonts()`** — реєструє веб-шрифти
- **Карта breakpoints**: `xxl: 1440px`, `xl: 1200px`, `lg: 992px`, `md: 768px`, `sm: 576px`

#### `styles.scss` — Головна точка входу
**Імпортує всі стилі** теми:
```scss
@use "sass:map";
@use "sass:meta";
@use "_mixins.scss" as mixins;
@use "fonts";
@use "header.scss";
@use "footer.scss";
@include meta.load-css("normalize.css/normalize.css");
```
**В цьому файлі всі typography теми:** 


#### `scripts.js` — Основна JS-точка входу
Експортує три основні класи:
- **`BaseElement`** — базовий клас для Custom Elements
- **`AdminAjaxClient`** — синглтон для AJAX запросів
- **`SupportUtility`** — утилити для HTML маніпуляцій

> **🎯 ВАЖЛИВО**: у цьому файлі **немає `import` на верхньому рівні**. Все працює через Custom Elements та динамічні скрипти в шаблонах!

---

### 1.2 Робота за зразком (Pattern Matching)

#### Інструкція для Copilot при роботі з новими файлами

**При отриманні завдання на нову секцію або файл (наприклад, `tpl-about.scss`):**

1. **Аналізуй структуру існуючих шаблонів**
   - Відкрий `blockse.scss` — це твій зразок!
   - Перевір, як там організовані селектори, медіа-запити, константи

2. **Копіюй логіку побудови**
   - Вкладеність блоків: `section.[profidev-name] > .theme-container > .theme-grid`
   - Порядок медіа-запитів: мобіль → планшет → десктоп
   - Структура Sass: селектори батька → елементи (`&__`) → модифікатори (`&--`)

3. **Використовуй констан та міксини як у зразках**
   - Шрифти: `var(--theme-fs-h2)`, `var(--theme-fs-mb-h2)` для мобілі
   - Відступи: `var(--theme-section-gap)`, `var(--theme-container-padding)`
   - Закруглення: `var(--theme-br-lg)`, `var(--theme-br-md)`
   - Перетворення: `#{mixins.toRem(20px)}`

4. **Не винаходь велосипед**
   - Якщо логіка вже є у `blockse.scss`, бери готове!
   - Адаптуй під нову сторінку, не переписуй з нуля

---


**Крок 4: Перевір результат**
```bash
npm run dev
```

---

## 🏗️ 2. HTML структура та CSS архітектура

### 2.1 Обов'язкова вкладеність елементів

**Критичне правило**: кожна секція повинна мати фіксовану структуру для збереження однакових відступів та сітки:

```
section.[profidev-prefix]
  ├── .theme-container (контейнер з max-width)
  │   └── .theme-grid (для сітки) або .wrapper (для flex)
  │       └── Вміст
```

#### Приклад 1: Герой секція з гридом
```html
<section class="profidev-hero">
  <div class="theme-container">
    <div class="theme-grid">
      <div class="profidev-hero__image">
        <img src="..." alt="..." />
      </div>
      <div class="profidev-hero__content">
        <h1>Заголовок</h1>
        <p>Текст</p>
      </div>
    </div>
  </div>
</section>
```

#### Приклад 2: Карти в ряд з флексом
```html
<section class="profidev-cards">
  <div class="theme-container">
    <div class="wrapper">  <!-- або .theme-grid -->
      <div class="profidev-cards__item">...</div>
      <div class="profidev-cards__item">...</div>
      <div class="profidev-cards__item">...</div>
    </div>
  </div>
</section>
```

### 2.2 Стратегія стилізації: гнучкість з WordPress

#### Принцип: відмова від суворого BEM

Замість строгого BEM (`block__element--modifier`), використовуй **практичний нейминг, близький до WordPress/Gutenberg**:

```scss
// ✅ ДОБРЕ — близько до структури контенту
.profidev-cards {
    display: grid;
}

.profidev-cards-item {
    // або просто як батько .profidev-cards далі
}

.profidev-cards .card-title {
    // Безпосередні елементи
}

// ❌ НАДТО СЛОЖНО — суворий BEM
.profidev-cards__item__title--large {
    // Занадто багато рівнів
}
```

#### Стилізація стандартних тегів для Gutenberg

**Всередину редактора Gutenberg** стандартні теги (`<h1>`, `<h3>`, `<p>`) повинні виглядати як на фронтенді:

```scss
.profidev-text-image {
	margin: var(--theme-section-gap) 0;
	overflow: hidden;

	&:has(.content.no-margin-top) {
			margin-top: 0!important;
	}

	&:has(.content.no-margin-bottom) {
			margin-bottom: 0!important;
	}

	.content {
		position: relative;
		z-index: 1;
		background-color: var(--wp--preset--color--color-5);
		color: var(--wp--preset--color--white);
		overflow: hidden;
		display: flex;

		.wrapper {
				display: flex;
				justify-content: space-between;
				width: 100%;
		}
	}
}
```

#### Використання кастомних класів з префіксом `.profidev-`

Всі основні блоки починалися з префіксу проекту для уникнення конфліктів:

```html
<!-- Блокована -->
<section class="profidev-hero-banner">
  <div class="profidev-hero-banner__content">
    <h1>Заголовок</h1>
  </div>
</section>

<!-- Карти -->
<section class="profidev-cards">
  <div class="profidev-cards-list">
    <div class="profidev-card">
      <h3 class="card-title">Назва</h3>
      <p class="card-description">Опис</p>
    </div>
  </div>
</section>
```

#### Гнучкість для ACF та нативних блоків

Якщо ти використовуєш ACF блоки або нативні Gutenberg блоки:

```scss
// 1. Створи обгортку, якщо потрібно
.profidev-acf-wrapper {
    // Твої стилі
}

// 2. Стилізуй вміст всередину безпечно
.profidev-acf-wrapper {
    .wp-block-paragraph {
        color: var(--wp--preset--color--color-7);
    }

    .wp-block-image {
        border-radius: var(--theme-br-lg);
    }
}

// 3. Або використовуй ACF селектори
[data-acf-field="my_field"] {
    // Стилі
}
```

---

## 🔧 3. JavaScript архітектура: Custom Elements вміст стандарту

### 3.1 Основна концепція

**Забудь про стандартні JS-селектори (`js-*` префікси) або jQuery бібліотеки.** 

У проекті **виключно Custom Elements** — це web components, які розширюють базовий клас `BaseElement`.

**Вся логіка прив'язується до кастомного HTML тега, не до CSS класів!**

#### Приклад неправильного та правильного підходу

❌ **СТАРО — JS селектори + EventListeners**
```html
<button class="js-toggle-menu">Меню</button>

<script>
document.querySelector('.js-toggle-menu').addEventListener('click', () => {
    document.querySelector('.js-menu').classList.toggle('is-open')
})
</script>
```

✅ **ПРАВИЛЬНО — Custom Element**
```html
<profidev-header></profidev-header>

<script type="module">
// В окремому файлі або в шаблоні
class ProfiDevHeader extends BaseElement {
    connectedCallback() {
        const btn = this.querySelector('[data-role="menu-trigger"]')
        btn?.addEventListener('click', () => this.toggleMenu())
    }

    toggleMenu() {
        this.classList.toggle('is-open')
    }
}

if (!customElements.get('profidev-header')) {
    customElements.define('profidev-header', ProfiDevHeader)
}
</script>
```

### 3.2 Як створити Custom Element (приклад ProfiDevAccordions)

#### Файл: `wp-content/themes/profi.dev/assets/js/components/profidev-accordion.js`

```javascript
import { BaseElement } from '../scripts.js'

class ProfiDevAccordions extends BaseElement {
	connectedCallback () {
		super.connectedCallback()
		this.items = Array.from(this.querySelectorAll(".accordion")).filter(item =>
			item.closest("profidev-accordions") === this
		)

		this.breakpoint = parseInt(this.getAttribute("data-breakpoint")) || null
		this.onlyOne = this.hasAttribute("data-only-one")
		this.isDefaultOpen = this.hasAttribute("data-default-open")

		this.init()

		if (this.breakpoint) {
			window.addEventListener("resize", () => this.handleResize())
		}
	}

	isAccordionMode () {
		return !this.breakpoint || window.innerWidth < this.breakpoint
	}

	init () {
		this.items.forEach((item, index) => {
			const btn = item.querySelector(".accordion-head")
			const content = item.querySelector(".accordion-content")
			if (!btn || !content) {return}

			this.updateItemState(item, index === 0)

			btn.addEventListener("click", (e) => {
				if (!this.isAccordionMode()) {return}
				e.stopPropagation()
				this.toggleItem(item)
			})
		})

		this.handleResize()
	}

	handleResize () {
		const accordionMode = this.isAccordionMode()

		if (this.breakpoint) {
			this.classList.toggle("is-disabled", !accordionMode)
		}

		this.items.forEach(item => this.updateItemState(item))
	}

	updateItemState (item, isFirst = false) {
		const content = item.querySelector(".accordion-content")
		const btn = item.querySelector(".accordion-head")
		if (!content || !btn) {return}

		if (!this.isAccordionMode()) {
			content.style.height = "auto"
			item.classList.remove("active")
			btn.setAttribute("aria-expanded", "true")
		} else {
			const shouldBeOpen = item.classList.contains("active") || (this.isDefaultOpen && isFirst)
			if (shouldBeOpen) {
				this.open(item, false)
			} else {
				this.close(item, false)
			}
		}
	}

	toggleItem (item) {
		const isActive = item.classList.contains("active")
		if (!isActive && this.onlyOne) {
			this.items.forEach(other => { if (other !== item) {this.close(other)} })
		}
		isActive ? this.close(item) : this.open(item)
	}

	open (item, animate = true) {
		const content = item.querySelector(".accordion-content")
		const btn = item.querySelector(".accordion-head")

		item.classList.add("active")
		btn.setAttribute("aria-expanded", "true")

		if (!animate) {
			content.style.height = "auto"
			return
		}

		content.style.height = "0px"
		content.style.height = content.scrollHeight + "px"

		const onEnd = () => {
			if (item.classList.contains("active")) {content.style.height = "auto"}
			content.removeEventListener("transitionend", onEnd)
		}
		content.addEventListener("transitionend", onEnd)
	}

	close (item, animate = true) {
		const content = item.querySelector(".accordion-content")
		const btn = item.querySelector(".accordion-head")

		item.classList.remove("active")
		btn.setAttribute("aria-expanded", "false")

		if (!animate) {
			content.style.height = "0px"
			return
		}

		content.style.height = content.scrollHeight + "px"
		requestAnimationFrame(() => {
			content.style.height = "0px"
		})
	}
}

if (!customElements.get("profidev-accordions")) {
	customElements.define("profidev-accordions", ProfiDevAccordions)
}
```

#### HTML у шаблоні:

```html
<profidev-accordions>
	<div class="accordion">
		<button class="accordion-head pre-sidebar-list with-icon">
			Table of contents</button>
		<div class="accordion-content">
			<ul class="sidebar-list">
				<li><a href="#content-1">Lorem Ipsum 1</a></li>
				<li><a href="#content-2">Lorem Ipsum 2</a></li>
				<li><a href="#content-3">About the Author</a></li>
			</ul>
		</div>
	</div>
</profidev-accordions>
```

#### CSS для слайдера:

```scss
.accordion {

	.accordion-head {
		font-size: var(--theme-fs-xl);
		line-height: var(--theme-lh-lg);
		color: var(--wp--preset--color--text-1);
		background-color: transparent;
		border: 0;

		&.with-icon {
			position: relative;

			&::after {
				content: "";
				width: #{mixins.toRem(11px)};
				height: #{mixins.toRem(7px)};
				display: inline-block;
				margin-left: #{mixins.toRem(20px)};
				mask-image: var(--theme-icon-chevron);
				background-color: var(--wp--preset--color--heading-2);
				mask-position: center;
				mask-repeat: no-repeat;
				mask-size: contain;
			}

		}
		}

		.accordion-content {
		overflow: hidden;
		width: 100%;
		transition: height var(--theme-transition);
		}

		.sidebar-list {
		display: flex;
		align-items: flex-start;
		justify-content: flex-start;
		flex-direction: column;
		gap: #{mixins.toRem(16px)};
		margin-bottom: #{mixins.toRem(24px)};
		padding: #{mixins.toRem(16px)};
		background-color: color-mix(
			in srgb,
			var(--wp--preset--color--cta-1) 10%,
			transparent
		);
		border-radius: var(--theme-br-lg);
		list-style-type: none;

		li {
			a {
				font-size: var(--theme-fs-sm);
				color: var(--wp--preset--color--heading-1);
			}
		}
		}

		&.active {

		.accordion-head {

			&::after {
				transform: rotate(180deg) translateY(#{mixins.toRem(2px)});
			}
		}
	}
}

```

### 3.3 Основне правило: NO `js-` селектори!

**Забудь про селектори на кшталт `.js-menu`, `.js-button`, `.js-toggle`.**

Замість цього використовуй:
- **Дата-атрибути**: `data-role="trigger"`, `data-action="open"`
- **Селектори по ID**: `id="my-unique-id"`
- **Селектори по тегу Custom Element**: `profidev-modal`, `profidev-slider`

---

## 🎨 4. Константи та змінні: забудь про hardcoded значення

### 4.1 🚫 КРИТИЧНЕ: ЗАБОРОНА на "магічні числа"

**Магічні числа** — це вільні значення у коді без змінних. Вони **НЕПРИЙНЯТНІ** в цьому проекті, тому що:
- Складно оновлювати дизайн систематично
- Легко забути одне значення, і дизайн виглядатиме невідповідно
- Код складно розуміти новому розробнику
- Порушує консистентність теми

#### ❌ НЕПРИЙНЯТНО:
```scss
.profidev-button {
    padding: 8px 16px; // ЩО це за 8px? ЧОМУ?
    border-radius: 5px; // Чому 5px, а не 8px?
    font-size: 14px; // Непомісна константа!
    gap: 10px; // Вольний вибір?
    margin-bottom: 43px; // ЧОму не 40px?
    background: #1A73E8; // Невідповідний колір!
}
```

#### ✅ ПРАВИЛЬНО:
```scss
.profidev-button {
    padding: #{mixins.toRem(8px)} #{mixins.toRem(16px)};
    border-radius: var(--theme-br-xs);
    font-size: var(--theme-fs-sm);
    gap: #{mixins.toRem(10px)};
    margin-bottom: var(--theme-section-gap);
    background: var(--wp--preset--color--color-1); // З theme.json
}
```

### 4.2 Приклади використання змінних

#### Розміри шрифтів (всегда мобіль → десктоп):
```scss
.profidev-section-title {
    // МОБІЛЬ - мала версія
    font-size: var(--theme-fs-mb-h3);
    line-height: var(--theme-lh-sm);

    // ДЕСКТОП - біла версія
    @include mixins.breakpoint(lg) {
        font-size: var(--theme-fs-h3);
        line-height: var(--theme-lh-lg);
    }
}
```

#### Кольори (з WordPress theme.json):
```scss
.profidev-button {
    background-color: var(--wp--preset--color--color-1);
    color: var(--wp--preset--color--white);
    border-color: var(--wp--preset--color--color-6);

    &:hover {
        background-color: var(--wp--preset--color--color-2);
    }
}
```

#### Відступи та проміжки:
```scss
.profidev-hero {
    margin: var(--theme-section-gap) 0;
    padding: var(--theme-container-padding);

    .theme-grid {
        gap: #{mixins.toRem(32px)};
    }
}
```

---

## 📱 5. Адаптивність: Desctop First підхід

### 5.1 Концепція Desctop First

1. **Напиши базові стилі для мобільних**
2. **Поступово розширяй** для більших екранів

Медіа-запити використовуються для **більших** екранів!

### 5.2 Приклади

#### Приклад 1: Лейаут
```scss
.profidev-hero {
    // МОБІЛЬ
    display: block;
    margin: var(--theme-section-gap) 0;

		//  - до 1200px
    @include mixins.breakpoint(xl) {
        gap: #{mixins.toRem(50px)};
    }

		//  - до 992px
    @include mixins.breakpoint(lg) {
        gap: #{mixins.toRem(30px)};
    }

    //  - др 768px
    @include mixins.breakpoint(md) {
        display: flex;
        gap: #{mixins.toRem(24px)};
    }

    
}
```

#### Приклад 2: Grid
```scss
.profidev-gallery {
    display: grid;
    grid-template-columns: 1fr;
    gap: #{mixins.toRem(16px)};    

    @include mixins.breakpoint(xl) {
        grid-template-columns: repeat(3, 1fr);
        gap: #{mixins.toRem(32px)};
    }

		@include mixins.breakpoint(md) {
        grid-template-columns: repeat(2, 1fr);
        gap: #{mixins.toRem(24px)};
    }
}
```

---

## 🔗 6. Синхронізація з Gutenberg

### 6.1 Проблема: редактор vs фронтенд

Редактор має інший контекст, базовий шрифт і стилі. Рішення: `.editor-styles-wrapper`

### 6.2 Стилізація для редактора

```scss
// Файл: blocks/MyBlock/style-editor.scss

.editor-styles-wrapper {
    // Стандартні теги повинні виглядати як на фронтенді!
    
    h1 {
        font-size: var(--theme-fs-h1);
        color: var(--wp--preset--color--color-6);
        margin-bottom: #{mixins.toRem(24px)};
    }

    h2, h3 {
        color: var(--wp--preset--color--color-6);
        margin-bottom: #{mixins.toRem(16px)};
    }

    p {
        color: var(--wp--preset--color--color-7);
        line-height: var(--theme-lh-lg);
    }

    .wp-block-image {
        border-radius: var(--theme-br-lg);
    }
}
```

### 6.3 Оптимальна схема

1. Основні стилі — `style.scss`
2. Редактор — `style-editor.scss`

```scss
// style.scss - для фронтенду
.profidev-section {
    padding: var(--theme-container-padding);
    margin: var(--theme-section-gap) 0;
}

// style-editor.scss - для редактора
.editor-styles-wrapper .profidev-section {
    padding: #{mixins.toRem(15px)};
    margin: #{mixins.toRem(20px)} 0;
    background: #f9f9f9;
    border: 2px dashed var(--theme-border-color);
}
```

---

## 📋 Чек-лист перед публікацією

- [ ] **Немає hardcoded чисел** — тільки змінні з `_constants.scss`
- [ ] **HTML структура правильна** — `section > .theme-container > .theme-grid`
- [ ] **Custom Elements мають `connectedCallback()`** та перевіряються `if (!customElements.get(...))`
- [ ] **Без `js-` селекторів** — тільки Custom Elements та `data-role`
- [ ] **Mobile-first стилі** — базові для мобілю, потім `@include mixins.breakpoint()`
- [ ] **Gutenberg стилі синхронізовані** — `style.scss` + `style-editor.scss`
- [ ] **Блоки мають префікс `.profidev-`** — уникнення конфліктів з WordPress
- [ ] **Шрифти, кольори, відступи з констант** — `var(--theme-*)`
- [ ] **Перевірено на мобілі, планшеті, десктопі**

---

## 🎯 Поширені помилки

### Помилка 1: Hardcoded числа
```scss
// ❌
padding: 20px;

// ✅
padding: #{mixins.toRem(20px)};
```

### Помилка 2: Custom Elements без перевірки
```javascript
// ❌
customElements.define('my-el', MyElement)

// ✅
if (!customElements.get('my-el')) {
    customElements.define('my-el', MyElement)
}
```

### Помилка 3: JS селектори вместо Custom Elements
```html
<!-- ❌ -->
<button class="js-open">Open</button>

<!-- ✅ -->
<profidev-modal id="modal"></profidev-modal>
<button data-action="open-modal" data-target="modal">Open</button>
```

### Помилка 4: Медіа-запити від меньшого до більшого
```scss
// ❌
@media (max-width: 768px) { }
@media (min-width: 769px) { }

// ✅
// базовий стиль
@include mixins.breakpoint(md) { }
```

### Помилка 5: Впливу на WordPress стилі
```scss
// ❌
.wp-block-image { border-radius: 50%; }

// ✅
.profidev-gallery .wp-block-image { border-radius: 50%; }
```

---

## 🔗 Корисні URL

- `https://localhost:4431/` — сайт
- `https://localhost:4431/pma/` — phpMyAdmin
- `wp-content/themes/profi.dev/` — корінь теми
- `wp-content/themes/profi.dev/assets/css/` — стилі

---


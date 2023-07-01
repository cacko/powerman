class _theme {

    #triggerElement;
    #isCustom;
    #currentTheme;
    #onThemeToggleHandler;
    #onPreferChangeHandler;

    constructor(el) {
        this.el = el;
        this.setTheme(this.storedScheme || this.preferredColorScheme);
    }

    get preferredColorScheme() {
        return window.matchMedia('(prefers-color-scheme: dark)')?.matches ? 'dark' : 'light';
    }

    get storedScheme() {
        const scheme = localStorage.getItem("selected_scheme");
        this.#isCustom = !!scheme;
        return scheme;
    }

    #storeScheme(theme) {
        localStorage.setItem("selected_scheme", theme);
        this.#isCustom = true;
    }

    start(selector) {
        this.#triggerElement = document.querySelector(selector);
        this.#startListeners();
        return true;
    }

    stop() {
        this.#stopListeners();
        return true;
    }


    #startListeners() {
        if (!this.#onPreferChangeHandler) {
            this.#onPreferChangeHandler = (ev) => this.#onPreferChangeHandler(ev);
            window.matchMedia('(prefers-color-scheme: dark)')
                .addEventListener('change', event => (ev) => this.#onPreferChangeHandler(ev));
        }
        if (!this.#onThemeToggleHandler) {
            this.#onThemeToggleHandler = (ev) => this.#onThemeToggle(ev);
            this.#triggerElement?.addEventListener("click", this.#onThemeToggleHandler, {passive: true});
        }
    }

    #stopListeners() {
        // window.matchMedia('(prefers-color-scheme: dark)')
        //     .removeEventListener('change', this.#onPreferChangeHandler);
        // this.#triggerElement?.removeEventListener("click", this.#onThemeToggleHandler);
    }

    #onPreferChange(ev) {
        const newColorScheme = event.matches ? "dark" : "light";
        if (!this.#isCustom) {
            this.setTheme(newColorScheme);
        }
    }

    #onThemeToggle(ev) {
        const theme = this.#currentTheme === "dark" ? "light" : "dark";
        this.setTheme(theme);
        this.#storeScheme(theme);
    }

    setTheme(theme) {
        this.root.setAttribute("data-bs-theme", theme);
        this.#currentTheme = theme;
    }


    get root() {
        return document.querySelector("html");
    }
};

export const Theme = new _theme();


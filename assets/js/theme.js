class _theme {

    #triggerElement;
    #isCustom;
    #currentTheme;

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
        window.matchMedia('(prefers-color-scheme: dark)')
            .addEventListener('change', event => (ev) => this.#onPreferChange(ev));
        this.#triggerElement?.addEventListener("click", (ev) => this.#onThemeToggle(ev));
    }

    #stopListeners() {
        window.matchMedia('(prefers-color-scheme: dark)')
            .removeEventListener('change', event => (ev) => this.#onPreferChange(ev));
        this.#triggerElement?.removeEventListener("click", (ev) => this.#onThemeToggle(ev));
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
        this.target.setAttribute("theme", theme);
        this.#currentTheme = theme;
    }


    get target() {
        return document.querySelector("html");
    }


};

export const Theme = new _theme();


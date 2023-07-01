import template from "lodash-es/template";
import AirDatepicker from 'air-datepicker';
import 'air-datepicker/air-datepicker.css';


class _form {

    constructor(target) {
        this.$target = target;
        this.init();
    }

    init() {
        this.#onWhen();
        this.#startListeners();
    }

    #startListeners() {
        this.$target
            .querySelectorAll("[name='state[when]']")
            .forEach((el) => el.addEventListener('click', () => this.#onWhen()));
    }

    #onWhen() {
        const formData = new FormData(this.$target);
        this.$target.querySelectorAll("[data-dependant]").forEach((el) => {
            const data = el.dataset.dependant;
            const [depends_name, depends_value] = data.split("_", 2);
            const readonly = formData.get(depends_name) !== depends_value;
            el.setAttribute('aria-readonly', `${readonly}`);
            el.querySelectorAll("input")
                .forEach((el) => readonly ? el.setAttribute("disabled", 'disabled') : el.removeAttribute('disabled'));
        });
    }



}

class _overlay {

    constructor(templateVars) {
        this.templateVars = templateVars;
        this.$template = document.querySelector("#dt-selector");
        this.$overlay = this.#init()
        this.$form = new _form(this.$overlay.querySelector("form"));
        this.$form.init();
    }

    #init() {
        const $overlayRoot = document.createElement("div");
        $overlayRoot.classList.add("overlay");
        $overlayRoot.append(this.$template.content.cloneNode(true));
        $overlayRoot.querySelectorAll("[data-template]").forEach((el) => {
            const innerTpl = template(el.dataset.template);
            const templateDst = el.dataset.template_dst;
            if (templateDst) {
                el.setAttribute(templateDst, innerTpl(this.templateVars));
            } else {
                el.innerHTML = innerTpl(this.templateVars);
            }
        });
        $overlayRoot.querySelectorAll("input[type='datetime-local']").forEach((el) => {
            const now = new Date();
            el.setAttribute("min", now.toISOString().split(".")[0])
        });
        return $overlayRoot;
    }

    #startListeners() {

        this.$overlay
            .querySelectorAll(".destroy")
            .forEach((el) => el.addEventListener("click", () => this.#destroy()));
    }


    #destroy() {
        this.$overlay.remove();
    }

    show() {
        const $container = this.$template.closest("div.container.root");
        $container.append(this.$overlay);

        this.#startListeners();
    }

}

class _dt_selector {
    constructor(selector) {
        this.selector = selector;
    }

    #startListeners() {
        document.querySelectorAll(this.selector).forEach((el) => {
            el.addEventListener("click", (ev) => this.#onClick(ev))
        });
    }

    #onClick(ev) {
        const evTarget = ev.currentTarget;
        const templateVars = evTarget.dataset;
        const overlay = new _overlay(templateVars);
        overlay.show();
    }

    start() {
        this.#startListeners();
    }

    stop() {
    }
};

export const DTSelector = new _dt_selector("button.dt_selector");

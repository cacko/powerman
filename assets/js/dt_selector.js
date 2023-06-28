import template from "lodash-es/template";
import AirDatepicker from 'air-datepicker';
import '@air-datepicker/air-datepicker.css';


class _form {

    constructor(target) {
        this.$target = target;
        this.#init();
    }

    get when() {
        return this.$target.querySelector("[name='when']:checked")?.value;
    }

    #init() {
        console.log(this.when);
    }

    #startListeners() {

    }

}

class _overlay {

    constructor(templateVars) {
        this.templateVars = templateVars;
        this.$template = document.querySelector("#dt-selector");
        this.$overlay = this.#init()
        this.$form = new _form(this.$overlay.querySelector("form"));
    }

    show() {
        const $container = this.$template.closest("div.container.root");
        $container.append(this.$overlay);

        new AirDatepicker('#dt');

        this.#startListeners();
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
        console.log(ev);
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

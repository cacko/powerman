import random from "lodash-es/random";
import shuffle from "lodash-es/shuffle";

class _rotator {

    static OUT_ANIMATIONS = ['animate__fadeOut'];
    static IN_ANIMATIONS = ['animate__fadeIn'];

    constructor(el) {
        this.el = el;
        this.running = false;
        this.images = Array.from(Array(7)).map((_, idx) => `/images/home/ws-background${idx}.webp`);
        this.timer = (ms) => {
            return new Promise((resolve) => {
                setTimeout(() => resolve(this.setBackground()), ms);
            });
        };

        (async () => await this.setBackground())();
    }

    start() {
        if (!this.running) {
            (async () => await this.timer(random(30, 50) * 1000))();
        }
        return true;
    }

    stop() {
        return this.timer;
    }

    async #fadeOut() {
        return new Promise(resolve => {
            if (!this.el.style.backgroundImage) {
                return resolve(false);
            }
            const fadeClasses = ['animate__animated', shuffle(_rotator.OUT_ANIMATIONS)[0], 'animate__slow'];
            this.el.classList.add(...fadeClasses);
            this.el.addEventListener('animationend', () => {
                this.el.classList.remove(...fadeClasses);
                resolve(true);
            }, {once: true});
        });
    }

    async #fadeIn() {
        return new Promise(resolve => {
            const fadeClasses = ['animate__animated', shuffle(_rotator.IN_ANIMATIONS)[0], 'animate__slow'];
            this.el.classList.add(...fadeClasses);
            this.el.addEventListener('animationend', () => {
                this.el.classList.remove(...fadeClasses);
                resolve(true);
            }, {once: true});
        });
    }

    async setBackground() {
        const src = shuffle(this.images)[0];
        await this.#fadeOut();
        this.el.style.backgroundImage = `url('${src}')`;
        await this.#fadeIn();
        return this.start();
    };
};

export const Rotator = new _rotator(document.querySelector(".ws-background"));

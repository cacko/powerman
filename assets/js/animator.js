import random from "lodash-es/random";
import shuffle from "lodash-es/shuffle";

class _animator {
    constructor(el) {
        this.el = el;
        this.children = null;
        if (this.el) {
            this.animations = el.dataset.animations.split(",");
            this.delay = el.dataset.delay;
            if (el.dataset.useChildren) {
                this.children = [...this.el.children].filter(el => !el.classList.contains('animator-false'));
            }
        }
    }

    start() {
        this.stop();
        if (this.el) {
            this.timer = setTimeout(() => this.setAnimation(), this.delay * 1000);
        }
        return !!this.timer;
    }

    stop() {
        return this.timer && clearTimeout(this.timer);
    }

    get target() {
        if (!this.children) {
            return this.el;
        }
        return shuffle(this.children)[0];

    }

    setAnimation() {
        const animation = shuffle(this.animations)[0];
        const addClasses = ['animate__animated', animation, 'animate__slow'];
        const target = this.target;
        target.classList.add(...addClasses);
        target.addEventListener('animationend', () => {
           target.classList.remove(...addClasses);
        }, {once: true});
        this.start();
    };
};

export const Animator = new _animator(document.querySelector(".uses-animator"));

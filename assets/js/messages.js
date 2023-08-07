import Toastify from 'toastify-js';

class _messages {

    constructor(nodes) {
        this.shown = false;
        this.toasts = [...nodes].map(toastEl => new Toastify({
            text: toastEl.querySelector(".body").innerText.trim(),
            gravity: "bottom", // `top` or `bottom`
            position: "left", // `left`, `center` or `right`
            stopOnFocus: true, // Prevents dismissing of toast on hover
            duration: 10000
        }));
    }

    start() {
        if (!this.shown) {
            this.toasts.forEach(t => t.showToast());
            this.shown = true;
        }
        return true;
    }

    stop() {
        return true;
    }
}

export const Messages = new _messages(document.querySelectorAll(".toast"));

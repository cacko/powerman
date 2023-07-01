// import random from "lodash-es/random";class _messages {
//
//     constructor(nodes) {
//         this.shown = false;
//         this.nodes = nodes;
//         this.toasts = [...nodes].map(toastEl => new Toast(toastEl));
//     }
//
//     start() {
//         if (!this.shown) {
//             this.toasts.forEach(t => t.show());
//             this.shown = true;
//         }
//         return true;
//     }
//
//     stop() {
//         return true;
//     }
// }
//
// export const Messages = new _messages(document.querySelectorAll(".toast"));

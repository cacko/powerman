import './styles/app.sass';
import 'animate.css';
import {Rotator} from "./js/rotator.js";
import {Animator} from "./js/animator";

window.addEventListener("load", () => {
    Rotator.start();
    Animator.start();
});
window.addEventListener("beforeunload", () => {
    Rotator.stop()
    Animator.stop();
});

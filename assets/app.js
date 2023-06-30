import './styles/app.sass';
import {Rotator} from "./js/rotator.js";
import {Animator} from "./js/animator";
import {Theme} from "./js/theme.js";

console.log = (data) => console.debug(data);

const onAway = () => Theme.stop() && Rotator.stop() && Animator.stop();
const onActive = () => Theme.start('.theme-switch') && Rotator.start() && Animator.start();

window.addEventListener("load", onActive);
window.addEventListener("beforeunload", onAway);

window.addEventListener("focus", onActive);
window.addEventListener("blur", onAway);

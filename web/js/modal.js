document.addEventListener('DOMContentLoaded', () => {
    const getAll = (selector) => Array.prototype.slice.call(document.querySelectorAll(selector), 0);
    const closeModals = () => {
        rootEl.classList.remove('is-clipped');
        $modals.forEach(($el) => {
            $el.classList.remove('is-active');
        });
    }

    let rootEl = document.documentElement;
    let $modals = getAll('.modal');
    let $modalButtons = getAll('.modal-button');
    let $modalCloses = getAll('.modal-background, .modal-close, .modal-card-head .delete, .modal-card-foot .button');
    
    if ($modalButtons.length > 0) {
        $modalButtons.forEach(($el) => {
            $el.addEventListener('click', () => {
                let target = $el.dataset.target;
                let $target = document.getElementById(target);
                rootEl.classList.add('is-clipped');
                $target.classList.add('is-active');
            });
        });
    }
    if ($modalCloses.length > 0) {
        $modalCloses.forEach(($el) => {
            $el.addEventListener('click', () => {
                closeModals();
            });
        });
    }
    document.addEventListener('keydown', (event) => {
        let e = event || window.event;
        if (e.keyCode === 27) {
            closeModals();
        }
    });
});
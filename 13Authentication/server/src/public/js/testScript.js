const state = {
  isMenuVisible: false,
};

const runTheScript = () => {
    state.isMenuVisible = !state.isMenuVisible;
    console.log(state.isMenuVisible);
    const trigger = document.querySelector(".trigger");
    state.isMenuVisible ? trigger.classList.add("bg-danger") : trigger.classList.remove("bg-danger");
};

function login(){
    const loginPanel = document.getElementById("login-panel").classList;
    loginPanel.add("loggedIn");
}
function pridatVysledek(){
    const addPanel = document.getElementById("add-result-panel").classList;
    addPanel.toggle("toggled");
}
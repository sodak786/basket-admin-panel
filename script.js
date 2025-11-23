function login(){
    const loginPanel = document.getElementById("login-panel").classList;
    loginPanel.add("loggedIn");
}
function togglePanel(){
    const addPanel = document.getElementById("add-result-panel").classList;
    addPanel.toggle("toggled");
}

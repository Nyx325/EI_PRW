export default class Alert {
    constructor(id){
        this.alert = document.getElementById(id);
    }

    setVisible(visible){
        if(visible) this.alert.classList.remove("d-none");
        else this.alert.classList.add("d-none");
    }

    setMessage(message){
        this.alert.innerText = `${message}`;
    }
}
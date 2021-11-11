var reading = null;

function addRead(textId, buttonId) {
    var text = document.getElementById(textId);
    var button = document.getElementById(buttonId);
    
    var utterance = new SpeechSynthesisUtterance(text.innerHTML);
    utterance.voice = speechSynthesis.getVoices()[0];
    utterance.onstart = () => {
        button.innerHTML = "Stop";
        reading = utterance;
    };
    utterance.onend = () => {
        button.innerHTML = "Read";
        reading = null;
    };
    
    button.onclick = () => {
        var readThis = reading != utterance;
        if (speechSynthesis.speaking) {
            speechSynthesis.cancel();
        }
        if (readThis) {
            speechSynthesis.speak(utterance);
        }
    };
}
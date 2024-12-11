function playButtonSound(event, link) {
    event.preventDefault();
    const buttonSound = document.getElementById('buttonSound');
    buttonSound.currentTime = 0;
    buttonSound.play().catch(() => {
        window.location.href = link;
    });
    setTimeout(() => {
        window.location.href = link;
    }, 1000);
}

const checkbox = document.getElementById('checkboxInput');
const music = document.getElementById('backgroundMusic');
checkbox.addEventListener('change', function() {
    if (checkbox.checked) {
        music.pause();
    } else {
        music.play();
    }
});
window.onload = function() {
    music.play();
};

/* Open when someone clicks on the span element */
function openNav() {
    document.getElementById("myNav").style.width = "100%";
  }
  
  /* Close when someone clicks on the "x" symbol inside the overlay */
  function closeNav() {
    document.getElementById("myNav").style.width = "0%";
  }
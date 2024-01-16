(function () {
  const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;

  // Set the countdown date to January 31, 2024
  const countdownDate = new Date('2024-01-31').getTime();

  const x = setInterval(function() {    
    const now = new Date().getTime(),
          distance = countdownDate - now;

    document.getElementById("days").innerText = Math.floor(distance / (day)),
    document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
    document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
    document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);

    // Actions when the countdown reaches the target date
    if (distance < 0) {
      document.getElementById("headline").innerText = "The date has arrived!";
      document.getElementById("countdown").style.display = "none";
      document.getElementById("content").style.display = "block";
      clearInterval(x);
    }
  }, 0)
}());

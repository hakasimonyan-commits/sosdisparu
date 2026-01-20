const foundBtn = document.getElementById("foundBtn");
const form = document.getElementById("alertForm");

let alreadyAsked = false;

foundBtn.addEventListener("click", function (e) {
    e.preventDefault();

    if (alreadyAsked) return;
    alreadyAsked = true;

    if (!navigator.geolocation) {
        alert("La géolocalisation n'est pas supportée sur cet appareil.");
        form.style.display = "block";
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function (position) {
            document.getElementById("lat").value = position.coords.latitude;
            document.getElementById("lng").value = position.coords.longitude;
            form.style.display = "block";
        },
        function () {
            // ⬅️ ԱՀԱ ՍԱ Է ԿԱՐԵՎՈՐ ՓՈՓՈԽՈՒԹՅՈՒՆԸ
            alert("Localisation indisponible. Vous pouvez continuer sans localisation.");
            form.style.display = "block";
            alreadyAsked = false;
        }
    );
});
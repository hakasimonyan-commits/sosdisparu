document.addEventListener('DOMContentLoaded', function () {
    console.log('JS READY');

    const btn = document.getElementById('foundBtn');
    const form = document.getElementById('alertForm');

    console.log(btn, form);

    if (!btn || !form) {
        console.error('Button կամ Form չի գտնվել');
        return;
    }

    btn.addEventListener('click', function () {
        alert('Button clicked');

        if (!navigator.geolocation) {
            alert("GPS non supporté");
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function (pos) {
                document.getElementById('lat').value = pos.coords.latitude;
                document.getElementById('lng').value = pos.coords.longitude;
                form.style.display = 'block';
                btn.style.display = 'none';
            },
            function (err) {
                alert(err.message);
            }
        );
    });
});
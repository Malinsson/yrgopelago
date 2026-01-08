//Login display script
const loginButton = document.getElementById('admin-login');
const loginForm = document.getElementById('admin-login-form');
if (loginButton) {
    loginButton.addEventListener('click', function() {
        loginForm.classList.toggle('hidden');
    });
}




// Script to handle dynamic booking form functionality
function initializeBookingForm(roomPrices, featurePrices) {
    const arrivalDateInput = document.getElementById('arrival-date');
    const departureDateInput = document.getElementById('departure-date');
    const roomTypeSelect = document.getElementById('room-type');
    const totalPriceDisplay = document.getElementById('total-price');
    const featureCheckboxes = document.querySelectorAll('input[name="features[]"]');

    //Calculate number of nights between arrival and departure
    function calculateNights() {
        if (!arrivalDateInput.value || !departureDateInput.value) return 0;

        const arrival = new Date(arrivalDateInput.value);
        const departure = new Date(departureDateInput.value);
        const nights = Math.floor((departure - arrival) / 86400000);

        return Math.max(0, nights);
    }

    //Check selected features and calculate their total price
    function calculateFeaturesTotalPrice() {
        let featuresTotal = 0;
        featureCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const featureName = checkbox.value;
                if (featurePrices[featureName] !== undefined) {
                    featuresTotal += featurePrices[featureName];
                }
            }
        });
        return featuresTotal;
    }

    //Update total price display
    function updateTotalPrice() {
        const roomId = roomTypeSelect.value;
        const roomPrice = roomPrices[roomId] || 0;
        const nights = calculateNights();
        const roomTotal = roomPrice * nights;
        const featuresTotal = calculateFeaturesTotalPrice();
        const totalPrice = roomTotal + featuresTotal;

        totalPriceDisplay.textContent = totalPrice.toFixed(2);
    }

    //Sets departure date based on arrival date, defaulting to one night stay
    function syncDepartureWithArrival() {
        if (!arrivalDateInput.value) return;

        const arrivalDate = new Date(arrivalDateInput.value);
        arrivalDate.setDate(arrivalDate.getDate() + 1);

        const minDepartureDate = `${arrivalDate.getFullYear()}-${String(arrivalDate.getMonth() + 1).padStart(2, '0')}-${String(arrivalDate.getDate()).padStart(2, '0')}`;
        departureDateInput.min = minDepartureDate;

        if (!departureDateInput.value || departureDateInput.value < minDepartureDate) {
            departureDateInput.value = minDepartureDate;
        }

        updateTotalPrice();
    }

    syncDepartureWithArrival();

    arrivalDateInput.addEventListener('change', function() {
        syncDepartureWithArrival();
    });

    departureDateInput.addEventListener('change', updateTotalPrice);
    roomTypeSelect.addEventListener('change', updateTotalPrice);

    featureCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateTotalPrice);
    });
}

document.addEventListener('DOMContentLoaded', function() {
    if (window.bookingData) {
        initializeBookingForm(window.bookingData.roomPrices, window.bookingData.featurePrices);
    }
});

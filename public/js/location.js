document.addEventListener("DOMContentLoaded", () => {
    // 1. Dynamic Auto-Populate Execution via External IPInfo Verification Layer
    // NOTE: Using the free global open context fallback route. Add tokens if explicitly configured.
    fetch('https://ipinfo.io/json')
        .then(response => {
            if (!response.ok) throw new Error('Network metadata tracking error failed.');
            return response.json();
        })
        .then(data => {
            if (data) {
                if (document.getElementById('country')) document.getElementById('country').value = data.country || 'Unknown';
                if (document.getElementById('region')) document.getElementById('region').value = data.region || 'Unknown';
                if (document.getElementById('city')) document.getElementById('city').value = data.city || 'Unknown';
            }
        })
        .catch(err => {
            console.error("AJAX Error parsing Location parameters: ", err);
            // Graceful fallback to prevent visual submission blocks
            document.getElementById('country').value = 'LK';
            document.getElementById('region').value = 'Western';
            document.getElementById('city').value = 'Colombo';
        });

    // 2. Strict Frontend Real-Time Age Validation Guardrails (Must be 24+ Years Old)
    const birthdayPicker = document.getElementById('birthday');
    if (birthdayPicker) {
        birthdayPicker.addEventListener('change', () => {
            const chosenDate = new Date(birthdayPicker.value);
            const standardToday = new Date();
            
            let evaluatedAge = standardToday.getFullYear() - chosenDate.getFullYear();
            const monthDifference = standardToday.getMonth() - chosenDate.getMonth();
            
            // Adjust variance calculation if standard tracking month/day marker hasn't passed
            if (monthDifference < 0 || (monthDifference === 0 && standardToday.getDate() < chosenDate.getDate())) {
                evaluatedAge--;
            }

            if (evaluatedAge < 24) {
                alert("Security Restriction: Independent drivers registering profiles inside EcoDrive must be at least 24 years old.");
                birthdayPicker.value = ''; // Hard reset tracking field parameters
            }
        });
    }
});
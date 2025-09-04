
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Get the input fields by their IDs
        const regNumberField = document.getElementById('aircraft_registration_number');
        const typeField = document.getElementById('ac_type');

        // Add event listener to the Aircraft Registration Number field
        regNumberField.addEventListener('input', handleRegistrationNumberChange);

        // Function to handle changes in Aircraft Registration Number field
        function handleRegistrationNumberChange() {
            let reg_num = regNumberField.value;
            const registrationNumber = reg_num.toLowerCase();

            // Define your mapping of registration numbers to aircraft types
            const typeMapping = {
                @foreach($aircrafts as $aircraft)
                '{{ strtolower($aircraft->registration_number) }}': '{{ $aircraft->ac_type }}',
                @endforeach
            };

            // Set the Aircraft Type field based on the mapping
            typeField.value = typeMapping[registrationNumber] || ''; // Set to empty string if no match


            if (typeMapping.hasOwnProperty(registrationNumber)) {
                typeField.value = typeMapping[registrationNumber];
            } else {
                typeField.value = ''; // Set to empty string for unknown registration numbers
            }
        }
    });

</script>

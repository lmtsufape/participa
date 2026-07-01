@once
    <script>
        document.addEventListener('change', function (event) {
            const input = event.target;

            if (!input.matches('.js-registration-file')) {
                return;
            }

            const allowedExtensions = @json(\App\Support\RegistrationFormFields::allowedFileExtensions());
            const message = @json(\App\Support\RegistrationFormFields::allowedFileTypesMessage());
            const file = input.files && input.files[0] ? input.files[0] : null;
            let feedback = input.parentElement.querySelector('.registration-file-feedback');

            if (!feedback) {
                feedback = document.createElement('span');
                feedback.className = 'registration-file-feedback invalid-feedback d-block';
                feedback.setAttribute('role', 'alert');
                input.insertAdjacentElement('afterend', feedback);
            }

            feedback.textContent = '';
            input.classList.remove('is-invalid');

            if (!file) {
                return;
            }

            const extension = file.name.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(extension)) {
                input.value = '';
                input.classList.add('is-invalid');
                feedback.textContent = message;
            }
        });
    </script>
@endonce

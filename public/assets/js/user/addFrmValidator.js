window.addEventListener('DOMContentLoaded', () => {

    // Récuperation des champs du formulaire
    const prenomInput = document.getElementById('user-prenom');
    const nomInput = document.getElementById('user-nom');
    const adresseInput = document.getElementById('user-adresse');
    const telephoneInput = document.getElementById('user-telephone');
    const emailInput = document.getElementById('user-email');
    const photoInput = document.getElementById('user-photo');
    const roleInput = document.getElementById('user-role');
    const frmAddUser = document.getElementById('addUserForm');
    const btnAjouter = document.getElementById('btnAddUser');

    // Désactiver le bouton Ajouter
    btnAjouter.disabled = true;

    // Permet d'afficher ou masquer le message d'erreur
    function showError(input, message)
    {
        const baliseP = input.nextElementSibling;
        // console.log(baliseP);
        if (message) {
            baliseP.textContent = message;
            input.classList.add('is-invalid');
            baliseP.classList.add('text-danger', 'fw-bold', 'small');
        }
        else
        {
            baliseP.textContent = "";
            input.classList.remove('is-invalid');
            baliseP.classList.remove('text-danger', 'fw-bold', 'small');
        }
    }

    // Validation du champ nom à la saisie
    nomInput.addEventListener('input', () => {
        const nom = nomInput.value.trim();
        const nomValidator = Validator.nameValidator("Le nom", 2, 50, nom);

        if (nomValidator)
        {
            showError(nomInput, nomValidator.message);

        } else
        {
            showError(nomInput, "");
        }

        checkFormValidaty();
    });

    // Validation du champ prenom à la saisie
    prenomInput.addEventListener('input', () => {
        const prenom = prenomInput.value.trim();
        const prenomValidator = Validator.nameValidator("Le prénom", 2, 50, prenom);

        if (prenomValidator)
        {
            showError(prenomInput, prenomValidator.message);

        } else
        {
            showError(prenomInput, "");
        }

        checkFormValidaty();
    });

    // Validation du champ telephone à la saisie
    telephoneInput.addEventListener('input', () => {
        const telephone = telephoneInput.value.trim();
        const telephoneValidator = Validator.phoneValidator("Le numéro de téléphone", 9, 17, telephone);

        if (telephoneValidator) 
        {
            showError(telephoneInput, telephoneValidator.message);
            
        } else 
        {
            showError(telephoneInput, "");
        }

        checkFormValidaty();
    });

    // Validation du champ email à la saisie
    emailInput.addEventListener('input', () => {
        const email = emailInput.value.trim();
        const emailValidator = Validator.emailValidator("L'email", email);

        if (emailValidator) 
        {
            showError(emailInput, emailValidator.message);
            
        } else 
        {
            showError(emailInput, "");
        }

        checkFormValidaty();
    });

    // Validation du champ adresse à la saisie
    adresseInput.addEventListener('input', () => {
        const adresse = adresseInput.value.trim();
        const adresseValidator = Validator.addressValidator("L'adresse", 2, 50, adresse);

        if (adresseValidator)
        {
            showError(adresseInput, adresseValidator.message);

        } else
        {
            showError(adresseInput, "");
        }

        checkFormValidaty();
    });

    // Validation du champ photo à la selection
    photoInput.addEventListener('change', () => {
        const photo = photoInput.files[0];
        
        if (!photo) 
        {
            showError(photoInput, 'La photo est obligatoire.');
            
        } else if (!photo.type.startsWith('image/')) {
            showError(photoInput, 'Le fichier doit être une image.');
        } else 
        {
            showError(photoInput, "");
        }

        checkFormValidaty();
    });

     // Validation du champ role à la selection
    roleInput.addEventListener('change', () => {
        
        if (roleInput.value === "") 
        {
            showError(roleInput, 'Veuillez selectionner un role.');
        } else 
        {
            showError(roleInput, "");
        }
        checkFormValidaty();
    });

    // Activer le bouton Ajouter si les champs sont valides
    function checkFormValidaty()
    {
        const nom = nomInput.value.trim();
        const prenom = prenomInput.value.trim();
        const adresse = adresseInput.value.trim();
        const telephone = telephoneInput.value.trim();
        const email = emailInput.value.trim();
        const role = roleInput.value.trim();

        const isNameValid = Validator.nameValidator("Le nom", 5, 40, nom) == null;
        const isPrenomValid = Validator.nameValidator("Le prénom", 5, 50, prenom) == null;
        const isAdresseValid = Validator.addressValidator("L'adresse", 5, 50, adresse) == null;
        const isTelephoneValid = Validator.phoneValidator("Le numéro de téléphone", 9, 17, telephone) == null;
        const isEmailValid = Validator.emailValidator("L'email", email) == null;
        const isRoleValid = role !== "";

        btnAjouter.disabled = !(
            isPrenomValid &&
            isNameValid &&
            isAdresseValid &&
            isTelephoneValid &&
            isEmailValid && 
            isRoleValid
        );
    }

    // Desactiver le bouton ajouter apres cliquer sur annuler
    frmAddUser.addEventListener('reset', () => {
        btnAjouter.disabled = true;
    });

});
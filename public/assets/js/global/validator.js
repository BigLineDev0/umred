class Validator
{
    // Permet de valider le mot de passe
    static passwordValidator(controlName, value, lengthWord)
    {
        return !value.length
            ? {error : true, message : `${controlName} est obligatoire.`}
            : value.length < lengthWord
            ? {error : true, message : `${controlName} doit contenir au moins ${lengthWord} caractères.`}
            : ((value != "") && (value.startsWith(" ") || value.endsWith(" ")))
            ? {error : true, message : `Les espaces de debut et de fin ne sont pas autorisés.`}
            : null
    }

    // Permet de valider une adresse email
    static emailValidator(controlName, value)
    {
        let format = "^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$";
        return !value.length
            ? { error : true, message : `${controlName} est obligatoire.` }
            : !value.match(new RegExp(format))
            ? { error : true, message : `${controlName} doit respecter ce format exemple@gmail.com.` }
            : null
    }

    // Permet de valider un numéro de téléphone
    static phoneValidator(controlName, minlength, maxlength, value)
    {
        let pattern = '^[0-9+]+(\.?[0-9]+)?$';
        return !value.length
            ? { error : true, message : `${controlName} est obligatoire.` }
            : !value.match(new RegExp(pattern))
            ? { error : true, message : `${controlName} ne doit contenir que des chiffres.` }
            : value.length < minlength
            ? {error : true, message : `${controlName} doit contenir au moins ${minlength} chiffres.`}
            : value.length > maxlength
            ? {error : true, message : `${controlName} doit contenir au plus ${maxlength} chiffres.`}
            : null
    }

    // Permet de valider un nom composé de chaines de caractères
    static nameValidator(controlName, minlength, maxlength, value)
    {
        let format = /^[A-Za-zÀ-ÿ '-]+$/;
        if (!value) {
            return { error : true, message : `${controlName} est obligatoire.` }
        }

        if (!value.match(new RegExp(format))) {
            return { error : true, message : `${controlName} ne doit contenir que des lettres.` }
        }

        if (value.length < minlength) {
            return {error : true, message : `${controlName} doit contenir au moins ${minlength} lettres.`}
        }

        if (value.length > maxlength) {
            return {error : true, message : `${controlName} ne doit pas contenir au plus ${maxlength} lettres.`}
        }

        if ((value != "") && (value.startsWith(" ") || value.endsWith(" "))) {
            return {error : true, message : `Les espaces de debut et de fin ne sont pas autorisés.`}
        }

        return null;
    }

    // Permet de valider une adresse
    static addressValidator(controlName, minlength, maxlength, value)
    {
        const isContainsNumber = /^(?=.*[0-9]).*$/;
        const isContainsUpperCase = /^(?=.*[A-Z]).*$/;
        const isContainsLowerCase = /^(?=.*[a-z]).*$/;
        const isContainsSymbol = /^(?=.*[,;.-]).*$/;

        if (!value) {
            return { error : true, message : `${controlName} est obligatoire.` }
        }

        if (isContainsSymbol.test(value)
            && !isContainsLowerCase.test(value)
            && !isContainsUpperCase.test(value)
            && !isContainsNumber.test(value)) {
            return { error : true, message : `Le '${controlName} ne doit pas que des caractères spéciaux.` }
        }

        if (isContainsNumber.test(value)
            && !isContainsLowerCase.test(value)
            && !isContainsUpperCase.test(value)
            && !isContainsSymbol.test(value)) {
            return { error : true, message : `Le '${controlName} ne doit pas des chiffres.` }
        }

        if (value.length < minlength) {
            return {error : true, message : `${controlName} doit contenir au moins ${minlength} lettres.`}
        }

        if (value.length > maxlength) {
            return {error : true, message : `${controlName} doit contenir au plus ${maxlength} lettres.`}
        }

        if ((value != "") && (value.startsWith(" ") || value.endsWith(" "))) {
            return {error : true, message : `Les espaces de debut et de fin ne sont pas autorisés.`}
        }

        return null;
    }
}

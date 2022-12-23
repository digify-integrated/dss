// Form validation rules
// Rule for password strength
$.validator.addMethod('password_strength', function(value) {
    if(value != ''){
        var re = /^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
        return re.test(value);
    }
    else{
        return true;
    }

}, 'Password must contain at least 1 lowercase, uppercase letter, number, special character and must be 8 characters or longer');

// Rule for legal age
$.validator.addMethod('employee_age', function(value, element, min) {
    var today = new Date();
    var birthDate = new Date(value);
    var age = today.getFullYear() - birthDate.getFullYear();
  
    if (age > min+1) { return true; }
  
    var m = today.getMonth() - birthDate.getMonth();
  
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) { age--; }
  
    return age >= min;
}, 'The employee must be at least 18 years old and above');
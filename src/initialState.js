const initialState = {
  hasError: false,
  errorMessage: null,
  code: null,
  content: '',
  isContentValid: false,
  contentValidationMessage: null,
  cipher: null,
  initVector: null,
  encoding: null,
  isEncryptionEnabled: false,
  password: '',
  isPasswordStrengthAcceptable: null,
  passwordStrengthMessage: 'Password is required',
  autoDeleteStrategy: 'viewed',
  viewsLimit: 10,
  storageTimeInDays: 14,
  isFormSending: false,
};

export default initialState;

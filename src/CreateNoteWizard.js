import initialState from "./initialState";
import {Fragment, useEffect, useState, useRef} from "react";
import {
  Box,
  Button,
  Container,
  CssBaseline, FormControl, FormControlLabel,
  Grid, Radio, RadioGroup,
  Step,
  StepLabel,
  Stepper, Switch,
  TextField, FormLabel,
  Typography, Slider
} from "@mui/material";
import { createTheme, ThemeProvider } from '@mui/material/styles';
import {
  addEncryptedNote,
  addNote,
  checkPasswordStrength,
  enableNoteAutoDeleteOnStorageTimeExpired,
  enableNoteAutoDeleteOnViewsLimitReached, encryptText
} from "./Api";
import CustomizedSnackbar from "./CustomizedSnackbar";

const theme = createTheme({
  palette: {
    background: {
      default: '#F9F9F9',
    },
    primary: {
      main: '#FFD52E',
      contrastText: '#1C1C1C',
    },
    secondary: {
      main: '#505050',
    },
  }
});

export default function CreateNoteWizard() {
  const [state, setState] = useState(initialState);

  const [activeStep, setActiveStep] = useState(0);

  const handleBack = () => {
    setActiveStep((prevActiveStep) => prevActiveStep - 1);
  };

  const steps = ['Note', 'Encryption', 'Deletion'];

  const handleNoteChange = (e) => {
    const content = e.target.value;
    setState({ ...state, content: content.length >= 600 ? content.substring(0, 600) : content, isContentValid: content.length > 0 });
  };

  const lastPassword = useRef('');

  useEffect(() => {
    const callCheckPasswordStrength = () => {
      checkPasswordStrength(state.password).then(response => {
        setState({
          ...state,
          isPasswordStrengthAcceptable: response.is_acceptable,
          passwordStrengthMessage: response.strength_level.charAt(0).toUpperCase() + response.strength_level.slice(1) + ' password',
        });

        lastPassword.current = state.password;
      });
    };

    if (state.password.length >= 6 && lastPassword.current !== state.password) {
      callCheckPasswordStrength();
    }
  }, [state]);

  const handlePasswordChange = (e) => {
    if (e.target.value.length === 0) {
      setState({
        ...state,
        password: e.target.value,
        isPasswordStrengthAcceptable: false,
        passwordStrengthMessage: 'Password is required',
      });
      return;
    }

    if (e.target.value.length < 6) {
      setState({
        ...state,
        password: e.target.value,
        isPasswordStrengthAcceptable: false,
        passwordStrengthMessage: 'Weak password',
      });
      return;
    }

    setState({ ...state, password: e.target.value });
  };

  const enableAutoDeleteStrategy = (code) => {
    switch (state.autoDeleteStrategy) {
      case 'storage_time_expired':
        enableNoteAutoDeleteOnStorageTimeExpired(code, state.storageTimeInDays);
        break;
      case 'views_limit_reached':
        enableNoteAutoDeleteOnViewsLimitReached(code, state.viewsLimit);
        break;
      default:
        enableNoteAutoDeleteOnViewsLimitReached(code, 1);
        break;
    }
  };

  const handleNoteSubmit = () => {
    const newActiveStep = activeStep + 1;
    setActiveStep(newActiveStep);

    if (newActiveStep === 2 && state.isEncryptionEnabled) {
      encryptText(state.content, state.password).then(response => {
        setState({
          ...state,
          cipher: response.cipher,
          initVector: response.init_vector,
          encoding: response.encoding,
        });
      });
    }

    if (newActiveStep === 3) {
      if (state.isEncryptionEnabled) {
        addEncryptedNote(state.cipher, state.initVector, state.encoding).then(response => {
          if (response.code === undefined) {
            setState({ ...state, errorMessage: 'Server error', hasError: true});
            return;
          }

          enableAutoDeleteStrategy(response.code);
          setState({ ...state, code: response.code });
        });
        return;
      }

      addNote(state.content).then(response => {
        if (response.code === undefined) {
          setState({ ...state, errorMessage: 'Server error', hasError: true});
          return;
        }

        enableAutoDeleteStrategy(response.code);
        setState({ ...state, code: response.code });
      });
    }
  };

  const handleEncryptionCheckboxChange = () => {
    const isEncryptionEnabled = !state.isEncryptionEnabled;

    if (isEncryptionEnabled) {
      setState({ ...state, isEncryptionEnabled: true });
      return;
    }

    setState({
      ...state,
      isEncryptionEnabled: false,
      password: '',
      passwordStrengthMessage: 'Password is required',
      isPasswordStrengthAcceptable: null,
    });
  };

  const handleAutoDeleteStrategyChange = (e) => {
    setState({ ...state, autoDeleteStrategy: e.target.value });
  };

  const handleViewsLimitChange = (e) => {
    setState({ ...state, viewsLimit: e.target.value });
  };

  const handleStorageTimeChange = (e) => {
    setState({ ...state, storageTimeInDays: e.target.value });
  };

  let isNextButtonDisabled = true;

  if (activeStep === 0 && state.isContentValid) {
    isNextButtonDisabled = false;
  }

  if (activeStep === 1 && (!state.isEncryptionEnabled || (state.isEncryptionEnabled && state.isPasswordStrengthAcceptable))) {
    isNextButtonDisabled = false;
  }

  if (activeStep === 2) {
    isNextButtonDisabled = false;
  }

  return <ThemeProvider theme={theme}>
    <CssBaseline />
    <CustomizedSnackbar
      open={state.hasError}
      setOpen={() => setState({ ...state, hasError: false })}
      severity="error"
      message={state.errorMessage}
    />
    <Grid
      container
      direction="column"
      justifyContent="space-between"
      alignItems="center"
      sx={{
        height: '100vh',
        paddingTop: '4rem',
        paddingBottom: '4rem',
      }}
    >
      <Container maxWidth="sm">
        <Stepper activeStep={activeStep} sx={{marginBottom: '3rem'}}>
          {steps.map((label, index) => {
            return (
              <Step key={index}>
                <StepLabel>{label}</StepLabel>
              </Step>
            );
          })}
        </Stepper>
      </Container>

      <Container maxWidth="sm">
        {activeStep === 0 && <TextField
          label="Content"
          multiline
          rows={6}
          color="secondary"
          sx={{width: '100%', marginBottom: '2rem'}}
          onChange={handleNoteChange}
          value={state.content}
          helperText="Maximum number of characters 600"
        />}

        {activeStep === 1 && <Box sx={{ display: 'flex', flexDirection: 'column' }}>
          <FormControlLabel
            control={<Switch checked={state.isEncryptionEnabled} color="secondary" onChange={handleEncryptionCheckboxChange} />}
            label="Enable"
            sx={{marginBottom: '1rem'}}
          />
          <TextField
            type="password"
            label="Password"
            sx={{marginBottom: '2rem'}}
            onChange={handlePasswordChange}
            value={state.password}
            color="secondary"
            disabled={!state.isEncryptionEnabled}
            error={state.isPasswordStrengthAcceptable !== null ? !state.isPasswordStrengthAcceptable : false}
            helperText={state.isEncryptionEnabled ? state.passwordStrengthMessage : null}
            required={state.isEncryptionEnabled}
          />
        </Box>}

        {activeStep === 2 && <Fragment>
          <FormControl sx={{marginBottom: '2rem'}}>
            <FormLabel id="auto-delete-strategy" color="secondary">When to delete a note?</FormLabel>
            <RadioGroup
              aria-labelledby="auto-delete-strategy"
              value={state.autoDeleteStrategy}
              onChange={handleAutoDeleteStrategyChange}
            >
              <FormControlLabel value="viewed" control={<Radio color="secondary" />} label="after a single viewing" />
              <FormControlLabel value="views_limit_reached" control={<Radio color="secondary" />} label="after reaching the maximum number of allowed views" />
              <FormControlLabel value="storage_time_expired" control={<Radio color="secondary" />} label="after the storage time expires" />
            </RadioGroup>
          </FormControl>

          {state.autoDeleteStrategy === 'views_limit_reached' && <Box>
            <FormLabel id="set-views-limit" color="secondary">Set the maximum number of allowed views</FormLabel>
            <Slider value={state.viewsLimit} min={1} max={10} step={1} onChange={handleViewsLimitChange} color="secondary" />
            <Typography gutterBottom>{state.viewsLimit} {state.viewsLimit === 1 ? 'view' : 'views'}</Typography>
          </Box>}

          {state.autoDeleteStrategy === 'storage_time_expired' && <Box>
            <FormLabel id="set-views-limit" color="secondary">Set the storage time in days</FormLabel>
            <Slider value={state.storageTimeInDays} min={1} max={30} step={1} onChange={handleStorageTimeChange} color="secondary" />
            <Typography gutterBottom>{state.storageTimeInDays} {state.storageTimeInDays === 1 ? 'day' : 'days'}</Typography>
          </Box>}
        </Fragment>}

        {activeStep === 3 && <Box sx={{display: 'flex', justifyContent: 'center', width: '100%'}}>
          {process.env.REACT_APP_URL + '/' + state.code}
        </Box>}
      </Container>

      <Container maxWidth="sm" sx={{ display: 'flex', justifyContent: 'center' }}>
        {activeStep < steps.length ? <Box sx={{ display: 'flex', flexDirection: 'row', pt: 2 }}>
          <Button
            variant="text"
            color="inherit"
            disabled={activeStep === 0}
            onClick={handleBack}
            sx={{ mr: 1 }}
          >
            Back
          </Button>
          <Box sx={{ width: '10px' }} />
          <Button
            variant="contained"
            onClick={handleNoteSubmit}
            disabled={isNextButtonDisabled}
          >
            {activeStep < steps.length - 1 ? 'Next' : 'Save'}
          </Button>
        </Box> : <Button variant="text" color="inherit" href="/">Add another note</Button>}
      </Container>
    </Grid>
  </ThemeProvider>;
};

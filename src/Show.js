import { useParams } from "react-router-dom";
import { useEffect, useState } from "react";
import {decryptText, getNoteByCode} from "./Api";
import PageNotFound from "./PageNotFound";
import {Box, Button, CssBaseline, TextField, Typography} from "@mui/material";
import {createTheme, ThemeProvider} from "@mui/material/styles";
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

export default function Show() {
  const params = useParams();
  const [note, setNote] = useState({ content: null, cipher: null, initVector: null, encoding: null });
  const [isNotFound, setIsNotFound] = useState(false);
  const [isDecryptionError, setIsDecryptionError] = useState(false);
  const [password, setPassword] = useState('');

  const decrypt = () => {
    if (password.length === 0) {
      return;
    }

    decryptText(note.encoding, note.cipher, note.initVector, password).then(response => {
      if (response.error) {
        setIsDecryptionError(true);
        return;
      }

      setNote({ ...note, content: response.text });
    });
  };

  useEffect(() => {
    getNoteByCode(params.code)
      .then(response => {
        if (response.error) {
          setIsNotFound(true);
          return;
        }

        setNote({
          content: response.content,
          cipher: response.cipher,
          initVector: response.init_vector,
          encoding: response.encoding,
        });
      });
  }, [params.code, setIsNotFound]);

  return <ThemeProvider theme={theme}>
    <CssBaseline />
    {isNotFound ? <PageNotFound /> : <Box
      sx={{
        display: 'flex',
        flexDirection: 'column',
        justifyContent: 'center',
        alignItems: 'center',
        height: '100vh',
        minWidth: '460px',
        minHeight: '540px',
    }}
    >
      <Typography sx={{marginBottom: '2rem'}}>
        {note.content || (note.cipher && atob(note.cipher))}
      </Typography>
      {note.cipher !== null && note.content === null && <Box sx={{display: 'flex', flexDirection: 'column'}}>
        <TextField
          label="Password"
          type="password"
          value={password}
          onChange={e => setPassword(e.target.value)}
          sx={{marginBottom: '1rem'}}
          color="secondary"
        />
        <Button variant="contained" onClick={decrypt} disabled={password.length === 0}>Decrypt</Button>
      </Box>}
    </Box>}
    <CustomizedSnackbar
      open={isDecryptionError}
      setOpen={() => setIsDecryptionError(false)}
      severity="error"
      message="Decryption failed"
    />
  </ThemeProvider>;
}

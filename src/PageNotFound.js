import {Box, Typography} from "@mui/material";

export default function PageNotFound() {
  return <Box sx={{display: 'flex', flexDirection: 'column', justifyContent: 'center', alignItems: 'center', height: '100vh'}}>
    <Typography sx={{fontSize: '2rem'}}>Page not found</Typography>
  </Box>
}

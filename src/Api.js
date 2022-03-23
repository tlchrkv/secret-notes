const serverUrl = process.env.REACT_APP_API_URL;

export function checkPasswordStrength(password) {
  const url = new URL(serverUrl + '/api/v1/check-password-strength');
  url.searchParams.append('password', password);

  return fetch(url).then(response => response.json());
}

export function encryptText(text, passphrase) {
  const url = new URL(serverUrl + '/api/v1/encrypt-text');
  url.searchParams.append('text', text);
  url.searchParams.append('passphrase', passphrase);

  return fetch(url).then(response => response.json());
}

export function decryptText(encoding, cipher, initVector, passphrase) {
  const url = new URL(serverUrl + '/api/v1/decrypt-text');
  url.searchParams.append('encoding', encoding);
  url.searchParams.append('cipher', cipher);
  url.searchParams.append('init_vector', initVector);
  url.searchParams.append('passphrase', passphrase);

  return fetch(url).then(response => response.json());
}

export function addNote(content) {
  const url = new URL(serverUrl + '/api/v1/notes');
  const formData = new FormData();
  formData.append('content', content);

  return fetch(url, {
    method: 'POST',
    body: formData
  }).then(response => response.json());
}

export function addEncryptedNote(cipher, initVector, encoding) {
  const url = new URL(serverUrl + '/api/v1/encrypted-notes');
  const formData = new FormData();
  formData.append('cipher', cipher);
  formData.append('init_vector', initVector);
  formData.append('encoding', encoding);

  return fetch(url, {
    method: 'POST',
    body: formData
  }).then(response => response.json());
}

export function enableNoteAutoDeleteOnViewsLimitReached(code, viewsLimit) {
  const url = new URL(serverUrl + '/api/v1/notes/' + code + '/auto-delete/on-views-limit-reached');
  const formData = new FormData();
  formData.append('views_limit', viewsLimit);

  return fetch(url, {
    method: 'POST',
    body: formData
  }).then(response => response.json());
}

export function enableNoteAutoDeleteOnStorageTimeExpired(code, storageTimeInDays) {
  const url = new URL(serverUrl + '/api/v1/notes/' + code + '/auto-delete/on-storage-time-expired');
  const formData = new FormData();
  formData.append('storage_time_in_days', storageTimeInDays);

  return fetch(url, {
    method: 'POST',
    body: formData
  }).then(response => response.json());
}

export function getNoteByCode(code) {
  const url = new URL(serverUrl + '/api/v1/notes/' + code);

  return fetch(url).then(response => response.json());
}

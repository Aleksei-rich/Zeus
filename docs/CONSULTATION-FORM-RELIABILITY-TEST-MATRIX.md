# Consultation form reliability test matrix

Purpose: regression checklist for `Request Free Consultation`, especially multi-photo uploads. Customer-facing success must never depend on email attachment delivery.

## Limits

- Maximum files: 5
- Maximum per file: 10 MB
- Maximum combined upload: 15 MB
- Accepted: JPG/JPEG, PNG, WEBP, HEIC/HEIF, PDF
- Files are stored privately with the Consultation Request.
- Email notification is asynchronous. Large attachment sets remain in WordPress rather than blocking the customer response.

## Expected cases

| Case | Expected customer result | Server / notification expectation |
|---|---|---|
| No files | Normal submit and thank-you | Lead stored; email queued |
| 1 valid file | Upload progress, then thank-you | File stored privately; email queued |
| 3 valid files, 6.2 MB total | Upload progress, then thank-you | All files stored; email work does not block response |
| 5 valid files, 10.3 MB total | Upload progress, then thank-you | All files stored; large mail set may be sent without attachments, with admin link |
| 5 valid files, <=15 MB total | Valid | Same as above |
| 6+ files | Stop before upload; explain maximum is 5 and ask customer to reselect | No lead created |
| Any file >10 MB | Stop before upload; name offending file and show its size | No lead created |
| Combined size >15 MB | Stop before upload; show combined size and ask for fewer/smaller files | No lead created |
| Unsupported extension | Stop before upload; name file and list allowed formats | No lead created |
| Empty / zero-byte file | Stop before upload and ask customer to choose it again | No lead created |
| Invalid real MIME despite allowed extension | Server returns readable upload error in form | No lead created |
| PHP `post_max_size` exceeded | JSON 413 when XHR header reaches PHP; explain upload limits | No lead created |
| Web server / HTTP2 connection interruption | Keep form values/files on page, re-enable button, show retry guidance | Retry uses same submission id to avoid duplicate if first request was stored |
| Slow upload | Show percentage; 180s timeout becomes readable retry message | No indefinite `Sending…` state |
| Server 5xx | Re-enable button and show server retry message | No browser admin-post error page when response reaches XHR |
| Rate limit | Show readable wait-and-retry message | 429 JSON; no lead created |
| Expired nonce | Show refresh-and-retry message | 403 JSON; no lead created |
| Private file move failure | Show upload save error | Partially created lead removed |
| Email attachment delivery fails | Customer has already received success | Retry email without attachments; files remain private in WordPress |
| Large email attachment set | Customer has already received success | Email contains file list/admin link; originals remain private in WordPress |
| Response lost after lead stored, customer retries | Retry resolves as success | Existing lead found by `zeus_submission_id`; no duplicate lead |

## Production verification after deploy

1. `php -l` both plugin PHP files.
2. Confirm reliable handler is loaded and old multiupload handler is removed from `admin_post_nopriv_zeus_submit_consultation`.
3. Confirm `consultation-reliability.js` is present in consultation page source.
4. Send a harmless XHR probe with an invalid nonce and confirm a JSON 403 response rather than an HTML/admin-post error page.
5. Browser regression: retry the previously failing 5-photo ~10.3 MB set.
6. Confirm thank-you navigation, new private Consultation Request, all selected files available in WordPress, and notification status no longer blocks form completion.

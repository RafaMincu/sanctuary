import './bootstrap';
import * as Turbo from '@hotwired/turbo';

// Păstrăm submit-urile formularelor în fluxul Laravel clasic. Turbo Drive
// gestionează în continuare navigarea prin linkuri, fără să recreeze playerul.
Turbo.config.forms.mode = 'off';

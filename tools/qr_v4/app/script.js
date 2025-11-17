const QR_TYPES = [
  { id: 'text', label: 'Text' },
  { id: 'url', label: 'Länk' },
  { id: 'wifi', label: 'WiFi' },
  { id: 'vcard', label: 'Kontakt' },
  { id: 'email', label: 'E-post' },
  { id: 'sms', label: 'SMS' },
  { id: 'phone', label: 'Telefon' },
  { id: 'geo', label: 'Plats' }
];

const ICONS = {
  text: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 6h16M4 12h10M4 18h8" stroke-width="1.8" stroke-linecap="round"/></svg>',
  url: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M10 14l4-4m-6-1l-3 3a4 4 0 106 6l1-1m2-10l1-1a4 4 0 116 6l-3 3" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
  wifi: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M2.5 9a14 14 0 0119 0M6 12.5a9 9 0 0112 0M9.5 16a4 4 0 015 0M12 20h0" stroke-width="1.8" stroke-linecap="round"/></svg>',
  vcard: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="5" width="16" height="14" rx="2" ry="2" stroke-width="1.8"/><circle cx="10" cy="10" r="2" stroke-width="1.8"/><path d="M7 16c.3-1.5 1.7-3 3-3s2.8 1.5 3 3" stroke-width="1.8" stroke-linecap="round"/></svg>',
  email: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="4" y="6" width="16" height="12" rx="2" ry="2" stroke-width="1.8"/><path d="M4 8l8 5 8-5" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
  sms: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M4 5h16v11H7l-3 3z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
  phone: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M6 4h4l2 5-3 2a11 11 0 004 4l2-3 5 2v4a2 2 0 01-2 2A14 14 0 016 6a2 2 0 012-2z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
  geo: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 21s7-6 7-11a7 7 0 10-14 0c0 5 7 11 7 11z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="10" r="2.5" stroke-width="1.5"/></svg>',
  links: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M10 14l-2 2a4 4 0 105.8 5.5l2-2M14 10l2-2a4 4 0 10-5.8-5.5l-2 2" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>',
  felanmalan: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M12 3l9 4-9 4-9-4 9-4zm0 8v6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="19" r="1" fill="currentColor" stroke="none"/></svg>',
  textBatch: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M5 6h14M5 12h10M5 18h7" stroke-width="1.8" stroke-linecap="round"/></svg>'
};

const BATCH_TYPES = [
  { id: 'links', label: 'Länkar', placeholder: 'https://exempel.se', icon: 'links' },
  { id: 'felanmalan', label: 'Felanmälan', placeholder: 'NOD-1, adress...', icon: 'felanmalan' },
  { id: 'text', label: 'Text', placeholder: 'Valfri text...', icon: 'textBatch' }
];

const QUICK_STARTS = [
  {
    id: 'text',
    label: 'Text',
    description: 'Snabb enkel textkod',
    mode: 'single',
    type: 'text',
    data: { textInput: 'Välkommen till QR v4' }
  },
  {
    id: 'url',
    label: 'Länk',
    description: 'https://-adress för affischer',
    mode: 'single',
    type: 'url',
    data: { urlInput: 'https://mackan.eu' }
  },
  {
    id: 'felanmalan',
    label: 'Felanmälan (batch)',
    description: 'Förifylld nod/adress-lista',
    mode: 'batch',
    batchType: 'felanmalan',
    batchText: 'NOD-1, Adress 123\nNOD-2, Adress 456'
  }
];

const FORM_CONFIG = {
  text: [
    { id: 'textInput', label: 'Text', type: 'textarea', placeholder: 'Skriv text...' }
  ],
  url: [
    { id: 'urlInput', label: 'Länk', type: 'text', placeholder: 'https://...' }
  ],
  wifi: [
    { id: 'ssid', label: 'SSID', type: 'text' },
    { id: 'wifiPassword', label: 'Lösenord', type: 'text' },
    { id: 'encryption', label: 'Kryptering', type: 'select', options: ['WPA/WPA2', 'WEP', 'Ingen'] }
  ],
  vcard: [
    { id: 'firstName', label: 'Förnamn', type: 'text' },
    { id: 'lastName', label: 'Efternamn', type: 'text' },
    { id: 'email', label: 'E-post', type: 'email' },
    { id: 'phone', label: 'Telefon', type: 'text' },
    { id: 'company', label: 'Organisation', type: 'text' },
    { id: 'title', label: 'Titel', type: 'text' }
  ],
  email: [
    { id: 'emailAddress', label: 'Mottagare', type: 'email' },
    { id: 'emailSubject', label: 'Ämne', type: 'text' },
    { id: 'emailBody', label: 'Meddelande', type: 'textarea' }
  ],
  sms: [
    { id: 'smsNumber', label: 'Nummer', type: 'text' },
    { id: 'smsMessage', label: 'Meddelande', type: 'textarea' }
  ],
  phone: [
    { id: 'phoneNumber', label: 'Telefon', type: 'text' }
  ],
  geo: [
    { id: 'latitude', label: 'Latitud', type: 'text', placeholder: '59.3293' },
    { id: 'longitude', label: 'Longitud', type: 'text', placeholder: '18.0686' }
  ]
};

const state = {
  mode: 'single',
  selectedType: 'text',
  styles: {
    primary: '#111111',
    background: '#fefefe',
    size: 280,
    gradient: null,
    moduleShape: 'square',
    cornerShape: 'square',
    logoData: null,
    logoSize: 0.2
  },
  expert: false,
  qrInstance: null,
  currentData: '',
  history: [],
  batchType: 'links',
  batchEntries: [],
  generatedQRs: [],
  summaryData: '',
  warnings: [],
  lastFormSnapshot: null,
  templates: []
};

const schedulePreviewUpdate = debounce(() => regenerateFromForm(), 250);

const refs = {};

document.addEventListener('DOMContentLoaded', () => {
  cacheElements();
  initModeTabs();
  renderTypeTags();
  renderForm(state.selectedType);
  initQuickStart();
  initAdvancedControls();
  initHistory();
  initBatchControls();
  initExportButtons();
  setMode(state.mode);
  refs.formFields.addEventListener('input', handleFormInputChange);
  refs.formFields.addEventListener('change', handleFormInputChange);
  refs.generateBtn.addEventListener('click', handleGenerateSingle);
  refs.saveTemplate.addEventListener('click', handleSaveTemplate);
  refs.resetForm.addEventListener('click', () => resetForm(state.selectedType));
  refs.duplicateForm.addEventListener('click', handleDuplicate);
  refs.testMobile?.addEventListener('click', handleTestInMobile);
  refs.batchGenerate.addEventListener('click', handleGenerateBatch);
  refs.clearHistory.addEventListener('click', clearHistory);
  refs.clearTemplates?.addEventListener('click', clearTemplates);
  refs.openHelp.addEventListener('click', () => toggleHelpDrawer(true));
  refs.closeHelp.addEventListener('click', () => toggleHelpDrawer(false));
  refs.helpDrawer.addEventListener('click', (event) => {
    if (event.target === refs.helpDrawer) toggleHelpDrawer(false);
  });
  refs.clearLogo?.addEventListener('click', handleClearLogo);
  refs.openExpertDrawer?.addEventListener('click', () => toggleExpertDrawer(true));
  refs.closeExpertDrawer?.addEventListener('click', () => toggleExpertDrawer(false));
  refs.expertDrawer?.addEventListener('click', (event) => {
    if (event.target === refs.expertDrawer) toggleExpertDrawer(false);
  });
  createInitialQR();
  refreshWarnings();
  initTemplates();
  updateStatus('Redo');
});

function cacheElements() {
  refs.statusIndicator = document.getElementById('statusIndicator');
  refs.historyCount = document.getElementById('historyCount');
  refs.typeTags = document.getElementById('typeTags');
  refs.formFields = document.getElementById('formFields');
  refs.generateBtn = document.getElementById('generateBtn');
  refs.saveTemplate = document.getElementById('saveTemplate');
  refs.resetForm = document.getElementById('resetForm');
  refs.duplicateForm = document.getElementById('duplicateForm');
  refs.testMobile = document.getElementById('testMobile');
  refs.summaryType = document.getElementById('summaryType');
  refs.summaryData = document.getElementById('summaryData');
  refs.summaryStyle = document.getElementById('summaryStyle');
  refs.warningPanel = document.getElementById('warningPanel');
  refs.qrPreview = document.getElementById('qrPreview');
  refs.exportPanel = document.getElementById('exportPanel');
  refs.historyList = document.getElementById('historyList');
  refs.historyTemplate = document.getElementById('historyItemTemplate');
  refs.templateList = document.getElementById('templateList');
  refs.primaryColor = document.getElementById('primaryColor');
  refs.backgroundColor = document.getElementById('backgroundColor');
  refs.sizeSlider = document.getElementById('sizeSlider');
  refs.logoInput = document.getElementById('logoInput');
  refs.moduleShape = document.getElementById('moduleShape');
  refs.cornerShape = document.getElementById('cornerShape');
  refs.gradientStart = document.getElementById('gradientStart');
  refs.gradientEnd = document.getElementById('gradientEnd');
  refs.gradientType = document.getElementById('gradientType');
  refs.logoSize = document.getElementById('logoSize');
  refs.batchControls = document.getElementById('batchControls');
  refs.batchTextarea = document.getElementById('batchTextarea');
  refs.batchTypes = document.getElementById('batchTypes');
  refs.batchGenerate = document.getElementById('batchGenerate');
  refs.batchStatus = document.getElementById('batchStatus');
  refs.batchPreview = document.getElementById('batchPreview');
  refs.openHelp = document.getElementById('openHelp');
  refs.clearHistory = document.getElementById('clearHistory');
  refs.clearTemplates = document.getElementById('clearTemplates');
  refs.helpDrawer = document.getElementById('helpDrawer');
  refs.closeHelp = document.getElementById('closeHelp');
  refs.quickStart = document.getElementById('quickStartCards');
  refs.logoPreview = document.getElementById('logoPreview');
  refs.logoPreviewImage = refs.logoPreview?.querySelector('img') || null;
  refs.clearLogo = document.getElementById('clearLogo');
  refs.assistChips = {
    contrast: document.querySelector('[data-chip="contrast"]'),
    logo: document.querySelector('[data-chip="logo"]'),
    export: document.querySelector('[data-chip="export"]')
  };
  refs.openExpertDrawer = document.getElementById('openExpertDrawer');
  refs.closeExpertDrawer = document.getElementById('closeExpertDrawer');
  refs.expertDrawer = document.getElementById('expertDrawer');
}

function initModeTabs() {
  document.querySelectorAll('.qr-tab').forEach(btn => {
    btn.addEventListener('click', () => {
      setMode(btn.dataset.mode);
    });
  });
}

function setMode(mode = 'single') {
  if (!mode) return;
  state.mode = mode;
  document.querySelectorAll('.qr-tab').forEach(b => {
    const isActive = b.dataset.mode === mode;
    b.classList.toggle('qr-tab--active', isActive);
    b.setAttribute('aria-selected', isActive ? 'true' : 'false');
  });
  refs.batchControls.classList.toggle('hidden', state.mode !== 'batch');
  refs.batchPreview.classList.toggle('hidden', state.mode !== 'batch');
  refs.generateBtn.classList.toggle('hidden', state.mode === 'batch');
  refs.saveTemplate.classList.toggle('hidden', state.mode === 'batch');
  if (state.mode !== 'batch') {
    refs.batchStatus.textContent = '';
  } else {
    refs.exportPanel.querySelector('[data-export="zip"]').disabled = false;
  }
}

function renderTypeTags() {
  refs.typeTags.innerHTML = '';
  QR_TYPES.forEach(type => {
    const button = document.createElement('button');
    button.className = `qr-tag ${state.selectedType === type.id ? 'qr-tag--active' : ''}`;
    button.type = 'button';
    button.appendChild(createIconNode(type.id));
    const label = document.createElement('span');
    label.textContent = type.label;
    button.appendChild(label);
    button.addEventListener('click', () => {
      state.selectedType = type.id;
      renderTypeTags();
      renderForm(type.id);
      resetForm(type.id);
    });
    refs.typeTags.appendChild(button);
  });
}

function renderForm(type) {
  const config = FORM_CONFIG[type];
  refs.formFields.innerHTML = '';
  if (!config) return;
  config.forEach(field => {
    const wrapper = document.createElement('label');
    wrapper.textContent = field.label;
    const input =
      field.type === 'textarea'
        ? document.createElement('textarea')
        : document.createElement('input');
    if (field.type !== 'textarea') input.type = field.type || 'text';
    if (field.placeholder) input.placeholder = field.placeholder;
    input.id = field.id;
    input.required = true;
    if (field.type === 'select') {
      const select = document.createElement('select');
      select.id = field.id;
      field.options.forEach(opt => {
        const option = document.createElement('option');
        option.value = opt;
        option.textContent = opt;
        select.appendChild(option);
      });
      wrapper.appendChild(select);
    } else {
      wrapper.appendChild(input);
    }
    refs.formFields.appendChild(wrapper);
  });
}

function initQuickStart() {
  if (!refs.quickStart) return;
  refs.quickStart.innerHTML = '';
  QUICK_STARTS.forEach(item => {
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'qr-quickstart__card';
    button.innerHTML = `<h3>${item.label}</h3><p>${item.description || ''}</p>`;
    button.addEventListener('click', () => applyQuickStart(item));
    refs.quickStart.appendChild(button);
  });
}

function applyQuickStart(config) {
  setMode(config.mode || 'single');
  if (config.mode === 'batch') {
    if (config.batchType) {
      state.batchType = config.batchType;
      initBatchControls();
    }
    refs.batchTextarea.value = config.batchText || '';
    refs.batchTextarea.focus();
    refs.batchStatus.textContent = 'Snabbstart laddad – komplettera innan export.';
    updateStatus(`Snabbstart (${config.label})`);
    return;
  }
  if (config.type) {
    state.selectedType = config.type;
    renderTypeTags();
    renderForm(config.type);
    Object.entries(config.data || {}).forEach(([id, value]) => {
      const el = document.getElementById(id);
      if (el) el.value = value;
    });
    updateStatus(`Snabbstart (${config.label})`);
    schedulePreviewUpdate();
  }
}

function handleFormInputChange() {
  schedulePreviewUpdate();
}

function initAdvancedControls() {
  refs.primaryColor.addEventListener('input', (e) => {
    state.styles.primary = e.target.value;
    updateSummaryStyle();
    schedulePreviewUpdate();
  });
  refs.backgroundColor.addEventListener('input', (e) => {
    state.styles.background = e.target.value;
    updateSummaryStyle();
    schedulePreviewUpdate();
  });
  refs.sizeSlider.addEventListener('input', (e) => {
    state.styles.size = Number(e.target.value);
    updateSummaryStyle();
    schedulePreviewUpdate();
  });
  refs.logoInput.addEventListener('change', handleLogoUpload);
  refs.moduleShape.addEventListener('change', (e) => {
    state.styles.moduleShape = e.target.value;
    updateSummaryStyle();
    schedulePreviewUpdate();
  });
  refs.cornerShape.addEventListener('change', (e) => {
    state.styles.cornerShape = e.target.value;
    updateSummaryStyle();
    schedulePreviewUpdate();
  });
  refs.gradientType.addEventListener('change', handleGradientChange);
  refs.gradientStart.addEventListener('input', handleGradientChange);
  refs.gradientEnd.addEventListener('input', handleGradientChange);
  refs.logoSize.addEventListener('input', (e) => {
    state.styles.logoSize = Number(e.target.value) / 100;
    updateSummaryStyle();
    schedulePreviewUpdate();
  });
}

function handleGradientChange() {
  const type = refs.gradientType.value;
  if (type === 'none') {
    state.styles.gradient = null;
  } else {
    state.styles.gradient = {
      type,
      colorStops: [
        { offset: 0, color: refs.gradientStart.value },
        { offset: 1, color: refs.gradientEnd.value }
      ]
    };
  }
  updateSummaryStyle();
  schedulePreviewUpdate();
}

function initBatchControls() {
  refs.batchTypes.innerHTML = '';
  BATCH_TYPES.forEach(batch => {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = `qr-tag ${state.batchType === batch.id ? 'qr-tag--active' : ''}`;
    btn.appendChild(createIconNode(batch.icon));
    const label = document.createElement('span');
    label.textContent = batch.label;
    btn.appendChild(label);
    btn.addEventListener('click', () => {
      state.batchType = batch.id;
      refs.batchTextarea.placeholder = batch.placeholder;
      initBatchControls();
    });
    refs.batchTypes.appendChild(btn);
  });
  refs.batchTextarea.placeholder = BATCH_TYPES.find(b => b.id === state.batchType)?.placeholder || '';
}

function initExportButtons() {
  refs.exportPanel.querySelectorAll('[data-export]').forEach(btn => {
    btn.addEventListener('click', () => handleExport(btn.dataset.export));
  });
}

function toggleHelpDrawer(open) {
  if (open) {
    refs.helpDrawer.hidden = false;
    refs.helpDrawer.querySelector('.qr-drawer__panel').focus?.();
  } else {
    refs.helpDrawer.hidden = true;
    refs.openHelp.focus();
  }
}

function toggleExpertDrawer(open) {
  if (!refs.expertDrawer) return;
  if (open) {
    refs.expertDrawer.hidden = false;
    refs.expertDrawer.querySelector('.qr-drawer__panel').focus?.();
  } else {
    refs.expertDrawer.hidden = true;
    refs.openExpertDrawer?.focus();
  }
}

function createIconNode(key) {
  const span = document.createElement('span');
  span.className = 'qr-tag__icon';
  span.innerHTML = ICONS[key] || '';
  span.setAttribute('aria-hidden', 'true');
  return span;
}

function createInitialQR() {
  state.qrInstance = new QRCodeStyling({
    width: state.styles.size,
    height: state.styles.size,
    data: 'https://mackan.eu',
    type: 'svg',
    dotsOptions: {
      color: state.styles.primary,
      type: state.styles.moduleShape
    },
    cornersSquareOptions: {
      type: state.styles.cornerShape,
      color: state.styles.primary
    },
    cornersDotOptions: {
      color: state.styles.primary,
      type: state.styles.cornerShape === 'dot' ? 'dot' : 'square'
    },
    backgroundOptions: {
      color: state.styles.background
    },
    imageOptions: {
      crossOrigin: 'anonymous',
      margin: 12,
      imageSize: state.styles.logoSize
    }
  });
  state.qrInstance.append(refs.qrPreview);
}

function handleGenerateSingle() {
  if (!state.currentData) {
    const regenerated = regenerateFromForm();
    if (!regenerated) {
      alert('Fyll i alla obligatoriska fält innan du sparar.');
      return;
    }
  }
  if (!state.lastFormSnapshot?.fields) {
    alert('Kunde inte läsa formuläret för historik.');
    return;
  }
  saveHistoryEntry(state.selectedType, state.currentData, state.lastFormSnapshot.fields);
  updateStatus('QR sparad i historik');
}

function handleSaveTemplate() {
  const formData = collectFormData(state.selectedType);
  if (!formData) {
    alert('Generera minst en gång innan du sparar mall.');
    return;
  }
  const name = prompt('Namn på mall?');
  if (!name) return;
  const templates = JSON.parse(localStorage.getItem('qr_v4_templates') || '[]');
  templates.unshift({
    id: crypto.randomUUID(),
    name,
    type: state.selectedType,
    styles: cloneStyles(state.styles),
    data: formData,
    created: new Date().toISOString()
  });
  const trimmed = templates.slice(0, 10);
  state.templates = trimmed;
  localStorage.setItem('qr_v4_templates', JSON.stringify(trimmed));
  renderTemplates(trimmed);
  alert('Mallen sparades lokalt.');
}

function handleDuplicate() {
  if (!state.lastFormSnapshot) {
    alert('Inget tidigare formulär att duplicera.');
    return;
  }
  loadFormSnapshot(state.lastFormSnapshot);
  updateStatus('Formulär duplicerat');
}

async function handleTestInMobile() {
  if (!state.qrInstance) return;
  const blob = await state.qrInstance.getRawData('png');
  const url = URL.createObjectURL(blob);
  window.open(url, '_blank');
  setTimeout(() => URL.revokeObjectURL(url), 60_000);
}

function handleLogoUpload(event) {
  const file = event.target.files?.[0];
  if (!file) return;
  if (file.size > 500 * 1024) {
    alert('Loggan får max vara 500 KB.');
    event.target.value = '';
    return;
  }
  const reader = new FileReader();
  reader.onload = () => {
    const dataUrl = reader.result;
    const image = new Image();
    image.onload = () => {
      state.styles.logoData = dataUrl;
      showLogoPreview(dataUrl);
      updateSummaryStyle();
      schedulePreviewUpdate();
    };
    image.onerror = () => {
      alert('Kunde inte läsa logotypen. Försök med PNG eller SVG.');
      event.target.value = '';
    };
    image.src = dataUrl;
  };
  reader.readAsDataURL(file);
}

function handleClearLogo() {
  state.styles.logoData = null;
  if (refs.logoInput) refs.logoInput.value = '';
  hideLogoPreview();
  updateSummaryStyle();
  schedulePreviewUpdate();
}

function regenerateFromForm() {
  const formData = collectFormData(state.selectedType);
  if (!formData) {
    refs.summaryType.textContent = state.selectedType;
    refs.summaryData.textContent = 'Fyll i obligatoriska fält';
    refs.exportPanel?.setAttribute('aria-hidden', 'true');
    state.currentData = '';
    state.lastFormSnapshot = null;
    updateAssistPanel();
    return false;
  }
  const formatted = formatData(state.selectedType, formData);
  applyGeneratedState(formatted, formData);
  return true;
}

function applyGeneratedState(data, formData) {
  state.currentData = data;
  state.lastFormSnapshot = {
    type: state.selectedType,
    fields: JSON.parse(JSON.stringify(formData)),
    styles: cloneStyles(state.styles)
  };
  updateSummary(state.selectedType, data);
  updateQRInstance(data);
  refs.exportPanel?.setAttribute('aria-hidden', 'false');
  updateAssistPanel();
}

function updateQRInstance(data) {
  const options = {
    width: state.styles.size,
    height: state.styles.size,
    data,
    dotsOptions: {
      type: state.styles.moduleShape,
      color: state.styles.gradient ? undefined : state.styles.primary,
      gradient: state.styles.gradient || undefined
    },
    cornersSquareOptions: {
      type: state.styles.cornerShape === 'dot' ? 'square' : state.styles.cornerShape,
      color: state.styles.primary
    },
    cornersDotOptions: {
      type: state.styles.cornerShape === 'dot' ? 'dot' : 'square',
      color: state.styles.primary
    },
    backgroundOptions: {
      color: state.styles.background
    },
    image: state.styles.logoData || undefined,
    imageOptions: state.styles.logoData ? {
      margin: 12,
      imageSize: state.styles.logoSize,
      crossOrigin: 'anonymous',
      hideBackgroundDots: false
    } : undefined
  };
  state.qrInstance.update(options);
}

function collectFormData(type) {
  const config = FORM_CONFIG[type];
  if (!config) return null;
  const result = {};
  for (const field of config) {
    const el = document.getElementById(field.id);
    if (!el) {
      return null;
    }
    const value = el.value.trim();
    if (!value) {
      return null;
    }
    result[field.id] = value;
  }
  return result;
}

function formatData(type, data) {
  switch (type) {
    case 'text':
      return data.textInput;
    case 'url':
      return data.urlInput;
    case 'wifi':
      return `WIFI:T:${data.encryption || 'WPA'};S:${data.ssid};P:${data.wifiPassword};;`;
    case 'vcard':
      return [
        'BEGIN:VCARD',
        'VERSION:3.0',
        `FN:${data.firstName} ${data.lastName}`,
        `EMAIL:${data.email}`,
        `TEL:${data.phone}`,
        `ORG:${data.company}`,
        `TITLE:${data.title}`,
        'END:VCARD'
      ].join('\n');
    case 'email':
      return `mailto:${data.emailAddress}?subject=${encodeURIComponent(data.emailSubject)}&body=${encodeURIComponent(data.emailBody)}`;
    case 'sms':
      return `SMSTO:${data.smsNumber}:${data.smsMessage}`;
    case 'phone':
      return `TEL:${data.phoneNumber}`;
    case 'geo':
      return `geo:${data.latitude},${data.longitude}`;
    default:
      return '';
  }
}

function resetForm(type) {
  renderForm(type);
  state.styles.logoData = null;
  hideLogoPreview();
  if (refs.logoInput) refs.logoInput.value = '';
  refs.gradientType.value = 'none';
  state.styles.gradient = null;
  state.currentData = '';
  state.lastFormSnapshot = null;
  refs.exportPanel?.setAttribute('aria-hidden', 'true');
  clearSummary();
  updateSummaryStyle();
  refs.generateBtn.focus();
}

function updateSummary(type, data) {
  refs.summaryType.textContent = type;
  refs.summaryData.textContent = data.slice(0, 42) + (data.length > 42 ? '…' : '');
  state.summaryData = data;
  updateSummaryStyle();
}

function clearSummary() {
  refs.summaryType.textContent = state.selectedType || '–';
  refs.summaryData.textContent = '–';
  state.summaryData = '';
}

function updateSummaryStyle() {
  state.expert = hasExpertSettings(state.styles);
  const styleText = `Färg ${state.styles.primary}, bakgrund ${state.styles.background}${state.styles.logoData ? ', logga aktiv' : ''}${state.expert ? ', expertläge' : ''}`;
  refs.summaryStyle.textContent = styleText;
  refreshWarnings();
}

function showLogoPreview(src) {
  if (!refs.logoPreview || !refs.logoPreviewImage) return;
  refs.logoPreview.classList.remove('hidden');
  refs.logoPreviewImage.src = src;
}

function hideLogoPreview() {
  if (!refs.logoPreview || !refs.logoPreviewImage) return;
  refs.logoPreview.classList.add('hidden');
  refs.logoPreviewImage.src = '';
}

function saveHistoryEntry(type, data, formSnapshot) {
  const entry = {
    id: crypto.randomUUID(),
    type,
    data,
    form: formSnapshot,
    styles: cloneStyles(state.styles),
    created: new Date().toISOString()
  };
  state.history.unshift(entry);
  state.history = state.history.slice(0, 10);
  localStorage.setItem('qr_v4_history', JSON.stringify(state.history));
  renderHistory();
}

function initHistory() {
  state.history = JSON.parse(localStorage.getItem('qr_v4_history') || '[]');
  renderHistory();
}

function renderHistory() {
  refs.historyList.innerHTML = '';
  state.history.forEach(entry => {
    const dataString = typeof entry.data === 'string' ? entry.data : JSON.stringify(entry.data);
    const node = refs.historyTemplate.content.cloneNode(true);
    node.querySelector('.qr-history__type').textContent = entry.type;
    node.querySelector('.qr-history__data').textContent = dataString.slice(0, 24) + (dataString.length > 24 ? '…' : '');
    node.querySelector('.qr-history__date').textContent = new Date(entry.created).toLocaleTimeString();
    node.querySelector('button').addEventListener('click', () => {
      loadFormSnapshot({
        type: entry.type,
        fields: entry.form,
        styles: entry.styles
      });
      updateStatus('Historikpost laddad');
    });
    refs.historyList.appendChild(node);
  });
  refs.historyCount.textContent = state.history.length;
}

function clearHistory() {
  if (!confirm('Rensa historiken?')) return;
  state.history = [];
  localStorage.removeItem('qr_v4_history');
  renderHistory();
}

function loadTemplate(template) {
  loadFormSnapshot({
    type: template.type,
    fields: template.data,
    styles: template.styles
  });
  updateStatus(`Mallen "${template.name}" laddad`);
}

function loadFormSnapshot(snapshot) {
  setMode('single');
  state.selectedType = snapshot.type;
  renderTypeTags();
  renderForm(snapshot.type);
  Object.entries(snapshot.fields || {}).forEach(([key, value]) => {
    const el = document.getElementById(key);
    if (el) el.value = value;
  });
  if (snapshot.styles) {
    state.styles = cloneStyles(snapshot.styles);
    applyStylesToControls();
    updateSummaryStyle();
  }
  const formatted = formatData(snapshot.type, snapshot.fields || {});
  if (formatted) {
    state.currentData = formatted;
    updateQRInstance(formatted);
    refs.exportPanel.setAttribute('aria-hidden', 'false');
  }
}

function applyStylesToControls() {
  if (refs.primaryColor) refs.primaryColor.value = state.styles.primary;
  if (refs.backgroundColor) refs.backgroundColor.value = state.styles.background;
  if (refs.sizeSlider) refs.sizeSlider.value = state.styles.size;
  if (refs.logoSize) refs.logoSize.value = Math.round(state.styles.logoSize * 100);
  if (refs.moduleShape) refs.moduleShape.value = state.styles.moduleShape;
  if (refs.cornerShape) refs.cornerShape.value = state.styles.cornerShape;
  if (refs.gradientType) refs.gradientType.value = state.styles.gradient ? state.styles.gradient.type : 'none';
  if (state.styles.gradient) {
    if (refs.gradientStart) refs.gradientStart.value = state.styles.gradient.colorStops?.[0]?.color || '#111111';
    if (refs.gradientEnd) refs.gradientEnd.value = state.styles.gradient.colorStops?.[1]?.color || '#000000';
  } else {
    if (refs.gradientStart) refs.gradientStart.value = '#111111';
    if (refs.gradientEnd) refs.gradientEnd.value = '#000000';
  }
  if (state.styles.logoData) {
    showLogoPreview(state.styles.logoData);
  } else {
    hideLogoPreview();
  }
  state.expert = hasExpertSettings(state.styles);
}

function hasExpertSettings(styles) {
  return (
    styles.gradient ||
    styles.moduleShape !== 'square' ||
    styles.cornerShape !== 'square' ||
    styles.logoSize !== 0.2
  );
}

function cloneStyles(styles) {
  return JSON.parse(JSON.stringify(styles));
}

function initTemplates() {
  state.templates = JSON.parse(localStorage.getItem('qr_v4_templates') || '[]');
  renderTemplates();
}

function renderTemplates(templates = state.templates) {
  if (!refs.templateList) return;
  refs.templateList.innerHTML = '';
  templates.forEach(template => {
    const item = document.createElement('li');
    const button = document.createElement('button');
    button.className = 'qr-history__item';
    button.innerHTML = `
      <span class="qr-history__type">${template.type}</span>
      <span class="qr-history__data">${template.name || template.type}</span>
      <span class="qr-history__date">${new Date(template.created).toLocaleDateString()}</span>
    `;
    button.addEventListener('click', () => loadTemplate(template));
    item.appendChild(button);
    refs.templateList.appendChild(item);
  });
}

function clearTemplates() {
  if (!confirm('Rensa alla mallar?')) return;
  state.templates = [];
  localStorage.removeItem('qr_v4_templates');
  renderTemplates();
}

function handleGenerateBatch() {
  const lines = refs.batchTextarea.value
    .split('\n')
    .map(line => line.trim())
    .filter(Boolean);
  if (!lines.length) {
    alert('Ange minst en rad.');
    return;
  }
  refs.batchPreview.innerHTML = '';
  state.generatedQRs = [];
  const fragment = document.createDocumentFragment();
  lines.forEach((line, index) => {
    const formatted = formatBatchLine(state.batchType, line);
    const card = document.createElement('div');
    card.className = 'qr-batch-card';
    const title = document.createElement('p');
    title.textContent = line;
    title.className = 'qr-batch-card__title';
    const canvasWrapper = document.createElement('div');
    canvasWrapper.className = 'qr-batch-card__canvas';
    const controlRow = document.createElement('div');
    controlRow.className = 'qr-batch-card__controls';
    const downloadBtn = document.createElement('button');
    downloadBtn.type = 'button';
    downloadBtn.className = 'qr-button qr-button--outline';
    downloadBtn.textContent = 'PNG';
    const copyBtn = document.createElement('button');
    copyBtn.type = 'button';
    copyBtn.className = 'qr-button qr-button--ghost';
    copyBtn.textContent = 'Kopiera';

    card.appendChild(canvasWrapper);
    card.appendChild(title);
    controlRow.appendChild(downloadBtn);
    controlRow.appendChild(copyBtn);
    card.appendChild(controlRow);
    fragment.appendChild(card);
    const batchQR = new QRCodeStyling({
      width: 180,
      height: 180,
      data: formatted,
      dotsOptions: { color: state.styles.primary, type: state.styles.moduleShape },
      backgroundOptions: { color: state.styles.background }
    });
    batchQR.append(canvasWrapper);
    downloadBtn.addEventListener('click', () => {
      batchQR.download({ extension: 'png', name: `batch_${index + 1}` });
    });
    copyBtn.addEventListener('click', async () => {
      const blob = await batchQR.getRawData('png');
      await navigator.clipboard.write([new ClipboardItem({ 'image/png': blob })]);
      updateStatus('Batch-kopia klar');
    });
    state.generatedQRs.push({ name: `batch_${index + 1}`, data: formatted, instance: batchQR });
  });
  refs.batchPreview.appendChild(fragment);
  refs.batchPreview.classList.remove('hidden');
  refs.batchStatus.textContent = `${state.generatedQRs.length} QR-koder genererade.`;
  updateStatus('Batch klar');
}

function formatBatchLine(type, line) {
  if (type === 'felanmalan') {
    const [node, address = ''] = line.split(/[,;]+/).map(part => part.trim());
    const subject = encodeURIComponent(`Felanmälan ${node}`);
    return `mailto:felanmalan@example.com?subject=${subject}&body=${encodeURIComponent(address)}`;
  }
  if (type === 'links') return line;
  return line;
}

async function handleExport(format) {
  if (state.mode === 'batch' && format === 'zip') {
    await exportZip();
    return;
  }
  if (!state.qrInstance) return;
  if (state.warnings.length && ['png', 'svg', 'docx', 'zip'].includes(format)) {
    alert('Åtgärda markerade varningar innan export.');
    return;
  }
  switch (format) {
    case 'png':
    case 'svg':
      state.qrInstance.download({ extension: format, name: `qr-${Date.now()}` });
      break;
    case 'copy':
      await copyToClipboard();
      break;
    case 'docx':
      await exportDocx();
      break;
    case 'pdf':
      await exportPDF();
      break;
    default:
      alert('Exportformatet stöds inte ännu.');
  }
}

async function copyToClipboard() {
  const blob = await state.qrInstance.getRawData('png');
  await navigator.clipboard.write([
    new ClipboardItem({ 'image/png': blob })
  ]);
  updateStatus('Kopierad till urklipp');
}

async function exportZip() {
  if (!state.generatedQRs.length) {
    alert('Generera batch först.');
    return;
  }
  const zip = new JSZip();
  for (const entry of state.generatedQRs) {
    const blob = await entry.instance.getRawData('png');
    zip.file(`${entry.name}.png`, blob);
  }
  const content = await zip.generateAsync({ type: 'blob' });
  triggerDownload(content, `qr-batch-${Date.now()}.zip`);
  updateStatus('ZIP skapad');
}

async function exportDocx() {
  const doc = new docx.Document({
    sections: [
      {
        children: [
          new docx.Paragraph(state.summaryData || 'QR v4'),
          new docx.Paragraph({ text: new Date().toLocaleString() })
        ]
      }
    ]
  });
  const blob = await docx.Packer.toBlob(doc);
  triggerDownload(blob, `qr-${Date.now()}.docx`);
}

async function exportPDF() {
  const { jsPDF } = window.jspdf || {};
  if (!jsPDF) {
    alert('jsPDF kunde inte laddas.');
    return;
  }
  const doc = new jsPDF({ unit: 'mm', format: 'a4' });
  const margin = 15;
  const cellSize = 60;
  let x = margin;
  let y = margin;
  const items = state.mode === 'batch' && state.generatedQRs.length ? state.generatedQRs : [{ instance: state.qrInstance, name: 'qr-single' }];
  for (let i = 0; i < items.length; i++) {
    const item = items[i];
    const blob = await item.instance.getRawData('png');
    const dataUrl = await blobToDataURL(blob);
    doc.addImage(dataUrl, 'PNG', x, y, cellSize, cellSize);
    doc.text(item.name || `qr-${i + 1}`, x, y + cellSize + 6);
    x += cellSize + margin;
    if (x + cellSize > doc.internal.pageSize.getWidth() - margin) {
      x = margin;
      y += cellSize + 20;
    }
    if (y + cellSize > doc.internal.pageSize.getHeight() - margin) {
      doc.addPage();
      x = margin;
      y = margin;
    }
  }
  doc.save(`qr-${Date.now()}.pdf`);
}

function triggerDownload(blob, filename) {
  const link = document.createElement('a');
  link.href = URL.createObjectURL(blob);
  link.download = filename;
  link.click();
  URL.revokeObjectURL(link.href);
}

function updateStatus(text) {
  refs.statusIndicator.textContent = text;
  refs.statusIndicator.classList.add('status-pulse');
  setTimeout(() => refs.statusIndicator.classList.remove('status-pulse'), 1000);
}

function refreshWarnings() {
  state.warnings = evaluateWarnings();
  updateWarningPanel();
  updateAssistPanel();
}

function evaluateWarnings() {
  const warnings = [];
  const contrast = getContrast(state.styles.primary, state.styles.background);
  if (contrast < 2.8) {
    warnings.push('Kontrasten mellan modulfärg och bakgrund är för låg. Justera färgerna.');
  }
  if (state.styles.logoData && state.styles.logoSize > 0.28) {
    warnings.push('Logotypen täcker nästan maxytan (30 %). Minska storleken.');
  }
  if (state.styles.logoSize > 0.3) {
    warnings.push('Logotypen överskrider max 30 % av QR-ytan.');
  }
  return warnings;
}

function updateWarningPanel() {
  if (!refs.warningPanel) return;
  if (!state.warnings.length) {
    refs.warningPanel.classList.add('hidden');
    refs.warningPanel.textContent = '';
    return;
  }
  refs.warningPanel.classList.remove('hidden');
  refs.warningPanel.innerHTML = state.warnings.map(text => `<p>${text}</p>`).join('');
}

function updateAssistPanel() {
  if (!refs.assistChips) return;
  const hasContrastWarning = state.warnings.some(text => text.toLowerCase().includes('kontrasten'));
  if (refs.assistChips.contrast) {
    refs.assistChips.contrast.textContent = hasContrastWarning ? 'Kontrast: kontrollera' : 'Kontrast: OK';
    refs.assistChips.contrast.setAttribute('data-status', hasContrastWarning ? 'warn' : 'ok');
  }
  if (refs.assistChips.logo) {
    const status = state.styles.logoData ? 'ok' : 'muted';
    refs.assistChips.logo.textContent = state.styles.logoData ? 'Logga: aktiv' : 'Logga: saknas';
    refs.assistChips.logo.setAttribute('data-status', status);
  }
  if (refs.assistChips.export) {
    const blocked = state.warnings.length > 0 || !state.currentData;
    refs.assistChips.export.textContent = blocked ? 'Export: blockerad' : 'Export: redo';
    refs.assistChips.export.setAttribute('data-status', blocked ? 'warn' : 'ok');
  }
}

function getContrast(foreground, background) {
  const l1 = relativeLuminance(hexToRgb(foreground));
  const l2 = relativeLuminance(hexToRgb(background));
  const brighter = Math.max(l1, l2);
  const darker = Math.min(l1, l2);
  return (brighter + 0.05) / (darker + 0.05);
}

function hexToRgb(hex) {
  const normalized = hex.replace('#', '');
  if (normalized.length === 3) {
    const r = parseInt(normalized[0] + normalized[0], 16);
    const g = parseInt(normalized[1] + normalized[1], 16);
    const b = parseInt(normalized[2] + normalized[2], 16);
    return { r, g, b };
  }
  const bigint = parseInt(normalized, 16);
  return {
    r: (bigint >> 16) & 255,
    g: (bigint >> 8) & 255,
    b: bigint & 255
  };
}

function relativeLuminance({ r, g, b }) {
  const srgb = [r, g, b].map(value => {
    const channel = value / 255;
    return channel <= 0.03928 ? channel / 12.92 : Math.pow((channel + 0.055) / 1.055, 2.4);
  });
  return 0.2126 * srgb[0] + 0.7152 * srgb[1] + 0.0722 * srgb[2];
}

function debounce(fn, delay = 200) {
  let timeout;
  return (...args) => {
    clearTimeout(timeout);
    timeout = setTimeout(() => fn(...args), delay);
  };
}

function blobToDataURL(blob) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onloadend = () => resolve(reader.result);
    reader.onerror = reject;
    reader.readAsDataURL(blob);
  });
}


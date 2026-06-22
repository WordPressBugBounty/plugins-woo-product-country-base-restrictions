/*!
 * Zorem UI — ported from AST PRO's ast-settings.js
 * -----------------------------------------------------------------
 * Renamed in place by zorem-ui/__port_from_ast.py:
 *   .ast-settings-app → .zui-scope
 *   .ast-*            → .zui-*
 *   --ast-*           → --zui-*
 * AST PRO's own copy is untouched and continues to use .ast-* classes.
 */

/**
 * AST PRO - New Settings UI shell (Phase 1).
 *
 * Minimal vanilla JS: client-side section switching and the mobile sidebar drawer.
 * No save logic, no field logic (those arrive in later phases). The legacy admin
 * scripts are untouched.
 */
(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState !== 'loading') {
			fn();
		} else {
			document.addEventListener('DOMContentLoaded', fn);
		}
		document.addEventListener('ast:tabswapped', fn);
	}

	ready(function () {
		var app = document.getElementById('ast-settings-app');
		if (!app) {
			return;
		}

		var items = app.querySelectorAll('.zui-set-sidebar__item[data-section]');
		var panels = app.querySelectorAll('.zui-set-panel[data-section]');

		function closeDrawer() {
			app.classList.remove('ast-drawer-open');
		}

		function openDrawer() {
			app.classList.add('ast-drawer-open');
		}

		function activate(section) {
			var i;
			var found = false;

			for (i = 0; i < panels.length; i++) {
				var panelMatch = panels[i].getAttribute('data-section') === section;
				panels[i].classList.toggle('is-active', panelMatch);
				if (panelMatch) {
					panels[i].removeAttribute('hidden');
					found = true;
				} else {
					panels[i].setAttribute('hidden', 'hidden');
				}
			}

			if (!found) {
				return;
			}

			for (i = 0; i < items.length; i++) {
				var itemMatch = items[i].getAttribute('data-section') === section;
				items[i].classList.toggle('is-active', itemMatch);
				if (itemMatch) {
					items[i].setAttribute('aria-current', 'true');
				} else {
					items[i].removeAttribute('aria-current');
				}
			}

			closeDrawer();

			if (window.history && window.history.replaceState) {
				window.history.replaceState(null, '', '#' + section);
			}
		}

		Array.prototype.forEach.call(items, function (item) {
			item.addEventListener('click', function () {
				activate(item.getAttribute('data-section'));
			});
		});

		// Deep-link support: #<section> on load.
		if (window.location.hash) {
			activate(window.location.hash.replace('#', ''));
		}

		// Mobile drawer toggles.
		Array.prototype.forEach.call(app.querySelectorAll('[data-ast-drawer-toggle]'), function (btn) {
			btn.addEventListener('click', openDrawer);
		});
		Array.prototype.forEach.call(app.querySelectorAll('[data-ast-drawer-close]'), function (btn) {
			btn.addEventListener('click', closeDrawer);
		});

		/* ---- Custom multiselect (chips + dropdown) backed by the hidden native <select> ---- */
		function initMultiselect(root) {
			var select = root.querySelector('select.zui-set-ms__native');
			var control = root.querySelector('.zui-set-ms__control');
			var chipsWrap = root.querySelector('.zui-set-ms__chips');
			var dropdown = root.querySelector('.zui-set-ms__dropdown');
			if (!select || !control || !chipsWrap || !dropdown) {
				return;
			}
			var placeholder = root.getAttribute('data-placeholder') || 'Select…';

			function opts() {
				return Array.prototype.slice.call(select.options);
			}

			function commit() {
				// Native <select> is the source of truth; notify existing dirty-state handlers.
				select.dispatchEvent(new Event('change', { bubbles: true }));
				// Ensure the section Save button enables even for selects not wired in legacy JS
				// (e.g. #stripe_payment_methods, which the legacy handler does not listen for).
				Array.prototype.forEach.call(
					app.querySelectorAll('.zui-set-section-header .woocommerce-save-button[disabled]'),
					function (btn) { btn.disabled = false; }
				);
				renderChips();
				renderDropdown();
			}

			function renderChips() {
				chipsWrap.innerHTML = '';
				var selected = opts().filter(function (o) { return o.selected; });
				if (!selected.length) {
					var ph = document.createElement('span');
					ph.className = 'ast-set-ms__placeholder';
					ph.textContent = placeholder;
					chipsWrap.appendChild(ph);
					return;
				}
				selected.forEach(function (o) {
					var chip = document.createElement('span');
					chip.className = 'ast-set-ms__chip';
					var label = document.createElement('span');
					label.className = 'ast-set-ms__chip-label';
					label.textContent = o.textContent;
					var remove = document.createElement('button');
					remove.type = 'button';
					remove.className = 'ast-set-ms__chip-remove';
					remove.innerHTML = '&times;';
					remove.setAttribute('aria-label', 'Remove ' + o.textContent);
					remove.addEventListener('click', function (e) {
						e.stopPropagation();
						o.selected = false;
						commit();
					});
					chip.appendChild(label);
					chip.appendChild(remove);
					chipsWrap.appendChild(chip);
				});
			}

			function renderDropdown() {
				dropdown.innerHTML = '';
				opts().forEach(function (o) {
					var item = document.createElement('div');
					item.className = 'ast-set-ms__option' + (o.selected ? ' is-selected' : '');
					item.setAttribute('role', 'option');
					item.setAttribute('aria-selected', o.selected ? 'true' : 'false');
					var lbl = document.createElement('span');
					lbl.textContent = o.textContent;
					item.appendChild(lbl);
					if (o.selected) {
						var ck = document.createElement('span');
						ck.className = 'ast-set-ms__check';
						ck.innerHTML = '&#10003;';
						item.appendChild(ck);
					}
					item.addEventListener('click', function () {
						o.selected = !o.selected;
						commit();
					});
					dropdown.appendChild(item);
				});
			}

			function open() {
				root.classList.add('is-open');
				control.setAttribute('aria-expanded', 'true');
				dropdown.removeAttribute('hidden');
			}
			function close() {
				root.classList.remove('is-open');
				control.setAttribute('aria-expanded', 'false');
				dropdown.setAttribute('hidden', 'hidden');
			}

			control.addEventListener('click', function () {
				root.classList.contains('is-open') ? close() : open();
			});
			control.addEventListener('keydown', function (e) {
				if (e.key === 'Enter' || e.key === ' ') {
					e.preventDefault();
					root.classList.contains('is-open') ? close() : open();
				} else if (e.key === 'Escape') {
					close();
				}
			});
			document.addEventListener('click', function (e) {
				if (!root.contains(e.target)) {
					close();
				}
			});

			renderChips();
			renderDropdown();
		}

		Array.prototype.forEach.call(app.querySelectorAll('[data-ast-multiselect]'), initMultiselect);

		/* ---- CSV import: show/hide Import Day / Import Time rows by frequency ----
		   Re-implemented for the new .zui-set-row markup (legacy targets .multiple_select_li). */
		(function () {
			var freq = document.getElementById('import_frequency');
			if (!freq) {
				return;
			}
			var dayEl = document.getElementById('import_day');
			var timeEl = document.getElementById('import_time');
			var dayRow = dayEl ? dayEl.closest('.zui-set-row') : null;
			var timeRow = timeEl ? timeEl.closest('.zui-set-row') : null;

			function syncFrequency() {
				var v = freq.value;
				if (dayRow) {
					dayRow.style.display = (v === 'weekly') ? 'block' : 'none';
				}
				if (timeRow) {
					timeRow.style.display = (v === 'weekly' || v === 'daily') ? 'block' : 'none';
				}
			}

			freq.addEventListener('change', syncFrequency);
			syncFrequency();
		})();

		/* ---- FTP test-connection spinner ----
		   Legacy uses button.closest('li').find('.spinner') which is dead in the new
		   div-based markup. Drive the in-button spinner via an .is-testing class instead. */
		Array.prototype.forEach.call(app.querySelectorAll('.ftp_test_button'), function (btn) {
			var container = btn.closest('.ftp_test_container');
			var msg = container ? container.querySelector('.ftp_test_msg') : null;
			btn.addEventListener('click', function () {
				btn.classList.add('is-testing');
				if (msg) {
					var obs = new MutationObserver(function () {
						if (msg.textContent.trim() !== '') {
							btn.classList.remove('is-testing');
							obs.disconnect();
						}
					});
					obs.observe(msg, { childList: true, subtree: true, characterData: true });
				}
				// Safety net in case the request never resolves.
				setTimeout(function () { btn.classList.remove('is-testing'); }, 20000);
			});
		});

		/* ---- Order Statuses (OSM): row dim, live color preview, save-enable on color ---- */
		function enableSectionSave() {
			Array.prototype.forEach.call(
				app.querySelectorAll('.zui-set-section-header .woocommerce-save-button[disabled]'),
				function (b) { b.disabled = false; }
			);
		}

		Array.prototype.forEach.call(app.querySelectorAll('.zui-set-osm-row[data-osm-row]'), function (row) {
			var toggle = row.querySelector('.zui-set-toggle__input');
			var colorInput = row.querySelector('.zui-set-osm-color');
			var iconBox = row.querySelector('[data-osm-icon]');

			function syncEnabled() {
				if (!toggle) { return; }
				var on = toggle.checked;
				row.classList.toggle('is-disabled', !on);
				if (iconBox && colorInput) {
					iconBox.style.backgroundColor = on ? colorInput.value : '#cbd5e1';
				}
			}

			if (toggle) {
				toggle.addEventListener('change', syncEnabled);
			}
			if (colorInput) {
				var colorDot = row.querySelector('.zui-set-osm-color-dot');
				var colorHex = row.querySelector('.zui-set-osm-color-hex');
				colorInput.addEventListener('input', function () {
					if (iconBox && (!toggle || toggle.checked)) {
						iconBox.style.backgroundColor = colorInput.value;
					}
					if (colorDot) { colorDot.style.backgroundColor = colorInput.value; }
					if (colorHex) { colorHex.textContent = colorInput.value.toUpperCase(); }
				});
				// Color is not part of the legacy dirty-state hooks; enable Save on change.
				colorInput.addEventListener('change', enableSectionSave);
			}
		});

		// Rename "Completed"->"Shipped" is mutually exclusive with the standalone Shipped status.
		var renameToggle = document.getElementById('wc_ast_status_shipped');
		var shippedToggle = document.getElementById('wc_ast_status_new_shipped');
		if (renameToggle && shippedToggle) {
			renameToggle.addEventListener('change', function () {
				if (renameToggle.checked && shippedToggle.checked) {
					shippedToggle.checked = false;
					shippedToggle.dispatchEvent(new Event('change', { bubbles: true }));
				}
			});
		}
	});
})();

/**
 * AST PRO - Shipping Carriers tab interactions (Phase 3: full AJAX wiring).
 *
 * UI: 3-dot menu, modal open/close, bulk-select, API-name repeater.
 * Data: reuses the EXISTING AJAX endpoints (exact nonces/params) for every mutation,
 * plus three read-only endpoints (ast_carriers_grid / ast_carriers_disabled /
 * ast_carrier_details) that render the new design.
 */
(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState !== 'loading') { fn(); }
		else { document.addEventListener('DOMContentLoaded', fn); }
		document.addEventListener('ast:tabswapped', fn);
	}

	ready(function () {
		var root = document.getElementById('ast-set-carriers');
		if (!root) { return; }

		var ajaxurl = window.ajaxurl || (window.location.origin + '/wp-admin/admin-ajax.php');
		var NONCE = root.getAttribute('data-nonce'); // nonce_shipping_provider

		var grid = document.getElementById('ast-carriers-grid');
		var bulkbar = document.getElementById('ast-carriers-bulkbar');
		var bulkCount = bulkbar ? bulkbar.querySelector('.zui-bulk-count') : null;
		var addTileHTML = (grid && grid.querySelector('.zui-set-carrier-add-tile'))
			? grid.querySelector('.zui-set-carrier-add-tile').outerHTML : '';
		var currentSearch = '';

		/* ---------- AJAX helpers ---------- */
		function post(data) {
			var body = new URLSearchParams();
			Object.keys(data).forEach(function (k) {
				var v = data[k];
				if (Array.isArray(v)) { v.forEach(function (item) { body.append(k + '[]', item); }); }
				else { body.append(k, v); }
			});
			return fetch(ajaxurl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body: body.toString()
			});
		}
		function postForm(form) {
			return fetch(ajaxurl, { method: 'POST', credentials: 'same-origin', body: new FormData(form) });
		}
		function busy(el, on) { if (el) { el.classList.toggle('is-busy', !!on); } }

		/* ---------- Modals ---------- */
		function setSyncState(state) {
			var modal = document.getElementById('ast-modal-sync');
			if (!modal) { return; }
			Array.prototype.forEach.call(modal.querySelectorAll('.zui-set-sync-state'), function (el) {
				if (el.getAttribute('data-sync-state') === state) { el.removeAttribute('hidden'); }
				else { el.setAttribute('hidden', ''); }
			});
		}
		function openModal(name) {
			var modal = document.getElementById('ast-modal-' + name);
			if (!modal) { return null; }
			if (name === 'sync') { setSyncState('idle'); }
			modal.removeAttribute('hidden');
			return modal;
		}
		function closeAllModals() {
			Array.prototype.forEach.call(root.querySelectorAll('.zui-set-modal:not([hidden])'), function (m) {
				m.setAttribute('hidden', '');
			});
		}
		root.addEventListener('click', function (e) {
			var closer = e.target.closest('[data-modal-close]');
			if (closer) { var m = closer.closest('.zui-set-modal'); if (m) { m.setAttribute('hidden', ''); } }
		});
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeAllModals(); } });

		/* ---------- 3-dot menu ---------- */
		var menu = document.getElementById('ast-carriers-menu');
		var menuBtn = document.getElementById('ast-carriers-menu-btn');
		var menuList = menu ? menu.querySelector('.zui-menu__list') : null;
		function closeMenu() {
			if (menuList) { menuList.setAttribute('hidden', ''); }
			if (menuBtn) { menuBtn.setAttribute('aria-expanded', 'false'); }
		}
		if (menuBtn && menuList) {
			menuBtn.addEventListener('click', function (e) {
				e.stopPropagation();
				if (menuList.hasAttribute('hidden')) { menuList.removeAttribute('hidden'); menuBtn.setAttribute('aria-expanded', 'true'); }
				else { closeMenu(); }
			});
			document.addEventListener('click', function (e) { if (menu && !menu.contains(e.target)) { closeMenu(); } });
		}

		/* ---------- Bulk select ---------- */
		function refreshBulkBar() {
			var n = grid ? grid.querySelectorAll('.zui-carrier-select:checked').length : 0;
			if (bulkCount) { bulkCount.textContent = String(n); }
			if (bulkbar) { if (n > 0) { bulkbar.removeAttribute('hidden'); } else { bulkbar.setAttribute('hidden', ''); } }
		}
		function setAllSelected(state) {
			if (!grid) { return; }
			Array.prototype.forEach.call(grid.querySelectorAll('.zui-carrier-select'), function (cb) {
				cb.checked = state;
				var card = cb.closest('.zui-set-carrier-card');
				if (card) { card.classList.toggle('is-selected', state); }
			});
			refreshBulkBar();
		}
		if (grid) {
			grid.addEventListener('change', function (e) {
				var cb = e.target.closest('.zui-carrier-select');
				if (!cb) { return; }
				var card = cb.closest('.zui-set-carrier-card');
				if (card) { card.classList.toggle('is-selected', cb.checked); }
				refreshBulkBar();
			});
		}

		/* ---------- Menu / tile / banner actions ---------- */
		root.addEventListener('click', function (e) {
			var btn = e.target.closest('[data-action]');
			if (!btn || !root.contains(btn)) { return; }
			switch (btn.getAttribute('data-action')) {
				case 'enable': openModal('enable'); loadDisabled(true); break;
				case 'add': openModal('add'); break;
				case 'sync': openModal('sync'); break;
				case 'select-all':
				case 'select-all-global': setAllSelected(true); break;
				case 'deselect-all': setAllSelected(false); break;
			}
			closeMenu();
		});

		/* ---------- Grid refresh (new design) ---------- */
		function refreshGrid() {
			busy(grid, true);
			return post({ action: 'ast_carriers_grid', security: NONCE, search: currentSearch })
				.then(function (r) { return r.text(); })
				.then(function (html) {
					if (grid) { grid.innerHTML = addTileHTML + html; }
					busy(grid, false);
					refreshBulkBar();
				})
				.catch(function () { busy(grid, false); });
		}

		/* ---------- Card toggle: enable/disable ---------- */
		if (grid) {
			grid.addEventListener('change', function (e) {
				var cb = e.target.closest('.zui-carrier-status');
				if (!cb) { return; }
				post({ action: 'update_shipment_status', security: NONCE, id: cb.getAttribute('data-pid'), checked: cb.checked ? 1 : 0 })
					.then(function () { refreshGrid(); });
			});
		}

		/* ---------- Main search ---------- */
		var searchInput = document.getElementById('ast-carrier-search');
		if (searchInput) {
			var st;
			searchInput.addEventListener('input', function () {
				clearTimeout(st);
				st = setTimeout(function () { currentSearch = searchInput.value.trim(); refreshGrid(); }, 300);
			});
		}

		/* ---------- Remove Selected (bulk) ---------- */
		var removeBtn = document.getElementById('ast-carriers-remove-selected');
		if (removeBtn) {
			removeBtn.addEventListener('click', function () {
				var ids = [];
				Array.prototype.forEach.call(grid.querySelectorAll('.zui-carrier-select:checked'), function (cb) { ids.push(cb.value); });
				if (!ids.length) { return; }
				post({ action: 'update_provider_status', security: NONCE, providers_id: ids, data_remove_selected: 'selected-page' })
					.then(function () { refreshGrid(); });
			});
		}

		/* ---------- Edit: open + load details ---------- */
		root.addEventListener('click', function (e) {
			var editBtn = e.target.closest('.zui-set-carrier-edit');
			if (!editBtn) { return; }
			var card = editBtn.closest('.zui-set-carrier-card');
			var modal = openModal('edit');
			if (!modal) { return; }

			// Preview from the card (instant).
			if (card) {
				var name = ((card.querySelector('.zui-set-carrier-name') || {}).textContent || '').trim();
				var country = ((card.querySelector('.zui-set-carrier-country') || {}).textContent || '').trim();
				var logo = card.querySelector('.zui-set-carrier-logo');
				Array.prototype.forEach.call(modal.querySelectorAll('.zui-edit-carrier-name, .zui-edit-carrier-name-2'), function (el) { el.textContent = name; });
				var cEl = modal.querySelector('.zui-edit-carrier-country'); if (cEl) { cEl.textContent = country; }
				var lEl = modal.querySelector('.zui-edit-carrier-logo'); if (lEl && logo) { lEl.innerHTML = logo.innerHTML; }
			}

			var pid = editBtn.getAttribute('data-pid') || '';
			modal.querySelector('.zui-edit-provider-id').value = pid;
			modal.querySelector('.zui-edit-provider-type').value = editBtn.getAttribute('data-type') || '';

			// Reset fields, then load real values.
			setVal(modal, '.zui-edit-display-name', '');
			setVal(modal, '.zui-edit-url', '');
			setVal(modal, '.zui-edit-provider-name', '');
			setVal(modal, '.zui-edit-shipping-country', '');
			setVal(modal, '.zui-carrier-thumb-url', '');
			setVal(modal, '.zui-carrier-thumb-id', '');
			var def = modal.querySelector('input[name="make_provider_default"]'); if (def) { def.checked = false; }
			fillApiNames(modal, ['']);
			syncLogoRemove(modal.querySelector('.zui-set-upload'));

			post({ action: 'ast_carrier_details', security: NONCE, provider_id: pid })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					if (!res || !res.success) { return; }
					var d = res.data;
					setVal(modal, '.zui-edit-display-name', d.display_name || '');
					setVal(modal, '.zui-edit-url', d.tracking_url || '');
					setVal(modal, '.zui-edit-provider-name', d.provider_name || '');
					setVal(modal, '.zui-edit-shipping-country', d.shipping_country || '');
					setVal(modal, '.zui-carrier-thumb-url', d.thumb_url || '');
					setVal(modal, '.zui-carrier-thumb-id', d.thumb_id || '');
					if (def) { def.checked = !!d.is_default; }
					fillApiNames(modal, (d.api_names && d.api_names.length) ? d.api_names : ['']);
					syncLogoRemove(modal.querySelector('.zui-set-upload'));
				});
		});

		function setVal(scope, sel, val) { var el = scope.querySelector(sel); if (el) { el.value = val; } }

		/* ---------- Edit: API-name repeater + fill ---------- */
		function fillApiNames(modal, names) {
			var wrap = modal.querySelector('#ast-edit-apinames');
			if (!wrap) { return; }
			var rows = wrap.querySelectorAll('.zui-set-apiname-row');
			for (var i = rows.length - 1; i >= 1; i--) { rows[i].remove(); }
			var first = wrap.querySelector('.zui-set-apiname-row input');
			if (first) { first.value = names[0] || ''; }
			for (var j = 1; j < names.length; j++) {
				var row = document.createElement('div');
				row.className = 'ast-set-apiname-row';
				row.innerHTML = '<input type="text" name="api_provider_name[]" class="ast-set-field__input" placeholder="API Name">' +
					'<button type="button" class="ast-set-apiname-remove" title="Remove">&times;</button>';
				row.querySelector('input').value = names[j];
				wrap.appendChild(row);
			}
		}
		root.addEventListener('click', function (e) {
			var addBtn = e.target.closest('.zui-set-apiname-add');
			if (addBtn) {
				var wrap = document.getElementById('ast-edit-apinames');
				if (!wrap) { return; }
				var row = document.createElement('div');
				row.className = 'ast-set-apiname-row';
				row.innerHTML = '<input type="text" name="api_provider_name[]" class="ast-set-field__input" placeholder="API Name">' +
					'<button type="button" class="ast-set-apiname-remove" title="Remove">&times;</button>';
				wrap.appendChild(row);
				return;
			}
			var rm = e.target.closest('.zui-set-apiname-remove');
			if (rm) { var r = rm.closest('.zui-set-apiname-row'); if (r) { r.remove(); } }
		});

		/* ---------- Add / Edit form submit ---------- */
		var addForm = document.getElementById('ast-add-carrier-form');
		if (addForm) {
			addForm.addEventListener('submit', function (e) {
				e.preventDefault();
				postForm(addForm).then(function () { closeAllModals(); addForm.reset(); refreshGrid(); });
			});
		}
		var editForm = document.getElementById('ast-edit-carrier-form');
		if (editForm) {
			editForm.addEventListener('submit', function (e) {
				e.preventDefault();
				postForm(editForm).then(function () { closeAllModals(); refreshGrid(); });
			});
			var resetBtn = document.getElementById('ast-edit-reset');
			if (resetBtn) {
				resetBtn.addEventListener('click', function () {
					var pid = editForm.querySelector('.zui-edit-provider-id').value;
					if (!pid) { return; }
					post({ action: 'reset_default_provider', security: NONCE, provider_id: pid })
						.then(function () { closeAllModals(); refreshGrid(); });
				});
			}
		}

		/* ---------- Enable modal ---------- */
		var enableModal = document.getElementById('ast-modal-enable');
		var enableList = document.getElementById('ast-enable-list');
		var enableSearchEl = document.getElementById('ast-enable-search');
		var enableState = { page: 1, pages: 1, search: '' };

		function updateEnablePager() {
			if (!enableModal) { return; }
			var pg = enableModal.querySelector('.zui-enable-page');
			var pgs = enableModal.querySelector('.zui-enable-pages');
			if (pg) { pg.textContent = enableState.page; }
			if (pgs) { pgs.textContent = enableState.pages; }
			var prev = enableModal.querySelector('[data-enable-prev]');
			var next = enableModal.querySelector('[data-enable-next]');
			if (prev) { prev.disabled = enableState.page <= 1; }
			if (next) { next.disabled = enableState.page >= enableState.pages; }
		}
		function loadDisabled(reset) {
			if (reset) { enableState.page = 1; enableState.search = enableSearchEl ? enableSearchEl.value.trim() : ''; }
			if (enableList) { enableList.innerHTML = '<div class="ast-set-modal__loading">Loading…</div>'; }
			post({ action: 'ast_carriers_disabled', security: NONCE, page: enableState.page, search: enableState.search })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					if (!res || !res.success) { return; }
					if (enableList) { enableList.innerHTML = res.data.html; }
					enableState.page = res.data.page;
					enableState.pages = res.data.pages;
					updateEnablePager();
				});
		}
		if (enableSearchEl) {
			var et;
			enableSearchEl.addEventListener('input', function () {
				clearTimeout(et);
				et = setTimeout(function () { loadDisabled(true); }, 300);
			});
		}
		if (enableModal) {
			enableModal.addEventListener('click', function (e) {
				if (e.target.closest('[data-enable-prev]')) { if (enableState.page > 1) { enableState.page--; loadDisabled(false); } return; }
				if (e.target.closest('[data-enable-next]')) { if (enableState.page < enableState.pages) { enableState.page++; loadDisabled(false); } return; }
				var enableBtn = e.target.closest('.zui-set-enable-btn');
				if (enableBtn && !enableBtn.classList.contains('is-added')) {
					enableBtn.disabled = true;
					post({ action: 'update_shipment_status', security: NONCE, id: enableBtn.getAttribute('data-pid'), checked: 1 })
						.then(function () {
							// Keep the row in place; just mark it Added + disable. Grid updates in the background.
							enableBtn.classList.add('is-added');
							enableBtn.textContent = enableBtn.getAttribute('data-added-label') || 'Added';
							refreshGrid();
						})
						.catch(function () { enableBtn.disabled = false; });
				}
			});
		}

		/* ---------- Sync ---------- */
		var syncStartBtn = document.getElementById('ast-sync-start');
		if (syncStartBtn) {
			syncStartBtn.addEventListener('click', function () {
				var reset = document.getElementById('ast-sync-reset');
				var steps = document.getElementById('ast-sync-steps');
				setSyncState('syncing');
				if (steps) { steps.innerHTML = ''; addStep(steps, 'Connecting to Zorem servers…'); }
				post({ action: 'sync_providers', security: NONCE, reset_checked: (reset && reset.checked) ? 1 : 0 })
					.then(function (r) { return r.json(); })
					.then(function (res) {
						var d = (res && typeof res.added !== 'undefined') ? res : (res && res.data ? res.data : {});
						setText('.zui-sync-added', d.added || 0);
						setText('.zui-sync-updated', d.updated || 0);
						setText('.zui-sync-deleted', d.deleted || 0);
						fillSyncDetails(d);
						setSyncState('done');
						refreshGrid();
					})
					.catch(function () { setSyncState('idle'); });
			});
		}
		function addStep(container, text) {
			var d = document.createElement('div');
			d.className = 'ast-set-sync-step';
			d.innerHTML = '<svg class="ast-set-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span></span>';
			d.querySelector('span').textContent = text;
			container.appendChild(d);
		}
		function setText(sel, val) {
			var el = root.querySelector(sel);
			if (el) { el.textContent = String(val); }
		}
		function cleanSyncHtml(html) {
			var tmp = document.createElement('div');
			tmp.innerHTML = html || '';
			Array.prototype.forEach.call(tmp.querySelectorAll('a'), function (a) { a.remove(); });
			return tmp.innerHTML.trim();
		}
		function fillSyncDetails(d) {
			var box = document.getElementById('ast-sync-details');
			var toggle = document.getElementById('ast-sync-details-toggle');
			if (toggle) { toggle.textContent = toggle.getAttribute('data-show-label') || 'view details'; }
			if (!box) { return; }
			var inner = cleanSyncHtml(d.added_html) + cleanSyncHtml(d.updated_html) + cleanSyncHtml(d.deleted_html);
			var dbIcon = '<svg class="ast-set-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14a9 3 0 0 0 18 0V5"/><path d="M3 12a9 3 0 0 0 18 0"/></svg>';
			box.innerHTML = '<div class="ast-set-sync-details__head">' + dbIcon + '<span>Synchronized Providers:</span></div>' +
				(inner || '<div>✔ No changes — everything is already up to date.</div>');
			box.setAttribute('hidden', '');
		}

		var syncDetailsToggle = document.getElementById('ast-sync-details-toggle');
		if (syncDetailsToggle) {
			syncDetailsToggle.addEventListener('click', function () {
				var box = document.getElementById('ast-sync-details');
				if (!box) { return; }
				if (box.hasAttribute('hidden')) {
					box.removeAttribute('hidden');
					syncDetailsToggle.textContent = syncDetailsToggle.getAttribute('data-hide-label') || 'hide details';
				} else {
					box.setAttribute('hidden', '');
					syncDetailsToggle.textContent = syncDetailsToggle.getAttribute('data-show-label') || 'view details';
				}
			});
		}

		/* ---------- Logo upload / remove ---------- */
		function syncLogoRemove(wrap) {
			if (!wrap) { return; }
			var urlInput = wrap.querySelector('.zui-carrier-thumb-url');
			var rm = wrap.querySelector('.zui-carrier-logo-remove');
			if (!rm) { return; }
			if (urlInput && urlInput.value) { rm.removeAttribute('hidden'); }
			else { rm.setAttribute('hidden', ''); }
		}
		root._astSyncLogoRemove = syncLogoRemove;

		root.addEventListener('click', function (e) {
			// Remove logo -> clears the field; on save the carrier reverts to its default logo.
			var rm = e.target.closest('.zui-carrier-logo-remove');
			if (rm) {
				e.preventDefault();
				var w = rm.closest('.zui-set-upload');
				if (w) {
					var u = w.querySelector('.zui-carrier-thumb-url');
					var i = w.querySelector('.zui-carrier-thumb-id');
					if (u) { u.value = ''; }
					if (i) { i.value = '0'; }
				}
				rm.setAttribute('hidden', '');
				return;
			}

			var up = e.target.closest('.zui-carrier-upload');
			if (!up) { return; }
			e.preventDefault();
			if (!window.wp || !window.wp.media) { return; }
			var frame = window.wp.media({ title: 'Select carrier logo', button: { text: 'Use logo' }, multiple: false });
			frame.on('select', function () {
				var att = frame.state().get('selection').first().toJSON();
				var wrap = up.closest('.zui-set-upload');
				if (wrap) {
					var urlInput = wrap.querySelector('.zui-carrier-thumb-url');
					var idInput = wrap.querySelector('.zui-carrier-thumb-id');
					if (urlInput) { urlInput.value = att.url; }
					if (idInput) { idInput.value = att.id; }
					syncLogoRemove(wrap);
				}
			});
			frame.open();
		});
	});
})();

/**
 * AST PRO - Integrations tab interactions.
 *
 * Client-side search, open the Edit modal (loads details via ast_integration_details),
 * and save via the unchanged integration_settings_popup_form_update handler. Field
 * names are set dynamically per integration (enable = integration key, autocomplete =
 * autocomplete_<id>).
 */
(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState !== 'loading') { fn(); }
		else { document.addEventListener('DOMContentLoaded', fn); }
		document.addEventListener('ast:tabswapped', fn);
	}

	ready(function () {
		var root = document.getElementById('ast-set-integrations');
		if (!root) { return; }

		var ajaxurl = window.ajaxurl || (window.location.origin + '/wp-admin/admin-ajax.php');
		var NONCE = root.getAttribute('data-nonce');
		var grid = document.getElementById('ast-intg-grid');
		var emptyEl = document.getElementById('ast-intg-empty');
		var modal = document.getElementById('ast-modal-intg');
		var form = document.getElementById('ast-intg-form');
		var currentIid = '';

		function post(data) {
			var b = new URLSearchParams();
			Object.keys(data).forEach(function (k) { b.append(k, data[k]); });
			return fetch(ajaxurl, {
				method: 'POST', credentials: 'same-origin',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body: b.toString()
			});
		}
		function postForm(f) { return fetch(ajaxurl, { method: 'POST', credentials: 'same-origin', body: new FormData(f) }); }
		function openModal() { if (modal) { modal.removeAttribute('hidden'); } }
		function closeModal() { if (modal) { modal.setAttribute('hidden', ''); } }

		root.addEventListener('click', function (e) { if (e.target.closest('[data-modal-close]')) { closeModal(); } });
		document.addEventListener('keydown', function (e) { if (e.key === 'Escape') { closeModal(); } });

		function setNames(sel, name) {
			var cb = modal.querySelector('.zui-intg-' + sel);
			var hd = modal.querySelector('.zui-intg-' + sel + '-hidden');
			if (cb) { cb.name = name || ''; }
			if (hd) { hd.name = name || ''; }
		}

		/* ---------- Open + load details ---------- */
		if (grid) {
			grid.addEventListener('click', function (e) {
				var card = e.target.closest('.zui-set-intg-card');
				if (!card) { return; }
				currentIid = card.getAttribute('data-iid');
				openModal();

				// Instant preview from the card.
				var nm = card.getAttribute('data-name') || '';
				Array.prototype.forEach.call(modal.querySelectorAll('.zui-intg-name, .zui-intg-name-2'), function (el) { el.textContent = nm; });
				var logoEl = modal.querySelector('.zui-intg-logo');
				var cardLogo = card.querySelector('.zui-set-intg-logo');
				if (logoEl && cardLogo) { logoEl.innerHTML = cardLogo.innerHTML; }

				post({ action: 'ast_integration_details', security: NONCE, integration_id: currentIid })
					.then(function (r) { return r.json(); })
					.then(function (res) {
						if (!res || !res.success) { return; }
						var d = res.data;
						Array.prototype.forEach.call(modal.querySelectorAll('.zui-intg-name, .zui-intg-name-2'), function (el) { el.textContent = d.name; });
						if (logoEl && d.logo) { logoEl.innerHTML = '<img src="' + d.logo + '" alt="">'; }
						var doc = modal.querySelector('.zui-intg-doc');
						if (doc) { doc.href = d.doc_url || 'https://docs.zorem.com/'; }
						var desc = modal.querySelector('.zui-intg-desc');
						if (desc) { desc.textContent = d.desc || ''; }

						var en = modal.querySelector('.zui-intg-enable');
						setNames('enable', d.id);
						if (en) { en.checked = !!d.enable; en.disabled = !!d.disabled; }

						var acRow = modal.querySelector('.zui-set-intg-ac-row');
						var ac = modal.querySelector('.zui-intg-ac');
						if (d.has_autocomplete) {
							if (acRow) { acRow.removeAttribute('hidden'); }
							setNames('ac', d.autocomplete_field);
							if (ac) { ac.checked = !!d.autocomplete_value; ac.disabled = !!d.disabled; }
						} else {
							if (acRow) { acRow.setAttribute('hidden', ''); }
							setNames('ac', '');
						}
					});
			});
		}

		/* ---------- Save ---------- */
		if (form) {
			form.addEventListener('submit', function (e) {
				e.preventDefault();
				var en = modal.querySelector('.zui-intg-enable');
				postForm(form).then(function () {
					var card = grid.querySelector('.zui-set-intg-card[data-iid="' + currentIid + '"]');
					if (card) {
						var active = en ? en.checked : false;
						card.classList.toggle('is-active', active);
						var st = card.querySelector('.zui-set-intg-status');
						if (st) { st.textContent = active ? 'Active' : 'Disabled'; }
					}
					closeModal();
					if (window.jQuery && jQuery(document).ast_snackbar) { jQuery(document).ast_snackbar('Integration saved'); }
				});
			});
		}

		/* ---------- Search (client-side) ---------- */
		var search = document.getElementById('ast-intg-search');
		if (search && grid) {
			search.addEventListener('input', function () {
				var q = search.value.trim().toLowerCase();
				var shown = 0;
				Array.prototype.forEach.call(grid.querySelectorAll('.zui-set-intg-card'), function (card) {
					var name = (card.getAttribute('data-name') || '').toLowerCase();
					var match = !q || name.indexOf(q) !== -1;
					card.style.display = match ? '' : 'none';
					if (match) { shown++; }
				});
				if (emptyEl) { if (shown === 0) { emptyEl.removeAttribute('hidden'); } else { emptyEl.setAttribute('hidden', ''); } }
			});
		}
	});
})();

/**
 * AST PRO - CSV Import tab UI helpers.
 *
 * The actual parse + per-row import + progress/logs is driven by the existing
 * shipping_row.js (bound to #wc_ast_upload_csv_form and the preserved hooks). This
 * module only adds new-design conveniences: the Choose-file button + filename label,
 * drag-and-drop into the file input, and counting success/fail for the Done stats.
 */
(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState !== 'loading') { fn(); }
		else { document.addEventListener('DOMContentLoaded', fn); }
		document.addEventListener('ast:tabswapped', fn);
	}

	ready(function () {
		var root = document.getElementById('ast-set-csv');
		if (!root) { return; }

		var fileInput = document.getElementById('trcking_csv_file');
		var chooseBtn = document.getElementById('ast-csv-choose');
		var fileLabel = document.getElementById('ast-csv-filename');
		var drop = document.getElementById('ast-csv-drop');

		function setFilename() {
			if (fileLabel) {
				fileLabel.textContent = (fileInput && fileInput.files && fileInput.files[0])
					? fileInput.files[0].name : 'No file chosen';
			}
		}

		if (chooseBtn && fileInput) {
			chooseBtn.addEventListener('click', function () { fileInput.click(); });
		}
		if (fileInput) {
			fileInput.addEventListener('change', setFilename);
		}

		/* ---------- Drag & drop into the file input ---------- */
		if (drop && fileInput) {
			['dragenter', 'dragover'].forEach(function (ev) {
				drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('is-drag'); });
			});
			['dragleave', 'dragend'].forEach(function (ev) {
				drop.addEventListener(ev, function () { drop.classList.remove('is-drag'); });
			});
			drop.addEventListener('drop', function (e) {
				e.preventDefault();
				drop.classList.remove('is-drag');
				var file = (e.dataTransfer && e.dataTransfer.files) ? e.dataTransfer.files[0] : null;
				if (!file) { return; }
				if (!/\.csv$/i.test(file.name)) { alert('Please drop a .csv file.'); return; }
				try {
					var dt = new DataTransfer();
					dt.items.add(file);
					fileInput.files = dt.files;
				} catch (err) { /* DataTransfer unsupported - user can use Choose file */ }
				setFilename();
			});
			drop.addEventListener('click', function () { fileInput.click(); });
		}

		/* ---------- Done-state success/fail stats ----------
		   shipping_row.js adds .csv_import_done to .bulk_upload_status_div when finished;
		   count the result <li>s by class to fill the stat widgets. */
		var statusDiv = root.querySelector('.bulk_upload_status_div');
		if (statusDiv && window.MutationObserver) {
			var obs = new MutationObserver(function () {
				if (!statusDiv.classList.contains('csv_import_done')) { return; }
				var ok = 0, fail = 0;
				Array.prototype.forEach.call(root.querySelectorAll('.csv_upload_status li'), function (li) {
					if (li.className === 'success') { ok++; } else { fail++; }
				});
				var okEl = root.querySelector('.zui-csv-stat-success');
				var failEl = root.querySelector('.zui-csv-stat-failed');
				if (okEl) { okEl.textContent = String(ok); }
				if (failEl) { failEl.textContent = String(fail); }
			});
			obs.observe(statusDiv, { attributes: true, attributeFilter: ['class'] });
		}

		// Reset stat numbers when the user restarts (shipping_row.js handles the rest).
		var again = root.querySelector('.csv_upload_again');
		if (again) {
			again.addEventListener('click', function () {
				var okEl = root.querySelector('.zui-csv-stat-success');
				var failEl = root.querySelector('.zui-csv-stat-failed');
				if (okEl) { okEl.textContent = '0'; }
				if (failEl) { failEl.textContent = '0'; }
				setFilename();
			});
		}
	});
})();

/**
 * AST PRO - License tab interactions.
 *
 * Usage-tracking save (posts to the unchanged wc_usage_tracking_form_update), plugin
 * grid All/Active/Add-ons filter + search, and the live "secure connection" clock.
 * License activate/deactivate is left to admin_pro.js (#wc_ast_pro_addons_form).
 */
(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState !== 'loading') { fn(); }
		else { document.addEventListener('DOMContentLoaded', fn); }
		document.addEventListener('ast:tabswapped', fn);
	}

	ready(function () {
		var root = document.getElementById('ast-set-lic');
		if (!root) { return; }

		var ajaxurl = window.ajaxurl || (window.location.origin + '/wp-admin/admin-ajax.php');

		/* ---------- Usage-tracking save ---------- */
		var form = document.getElementById('ast-usage-tracking-form');
		var msg = document.getElementById('ast-usage-msg');
		if (form) {
			form.addEventListener('submit', function (e) {
				e.preventDefault();
				fetch(ajaxurl, { method: 'POST', credentials: 'same-origin', body: new FormData(form) })
					.then(function () {
						if (msg) {
							msg.removeAttribute('hidden');
							setTimeout(function () { msg.setAttribute('hidden', ''); }, 2500);
						}
					});
			});
		}

		/* ---------- Plugin grid filter + search ---------- */
		var grid = document.getElementById('ast-lic-grid');
		var emptyEl = document.getElementById('ast-lic-empty');
		var searchEl = document.getElementById('ast-lic-search');
		var filter = 'all';

		function applyFilter() {
			if (!grid) { return; }
			var q = searchEl ? searchEl.value.trim().toLowerCase() : '';
			var shown = 0;
			Array.prototype.forEach.call(grid.querySelectorAll('.zui-set-lic-plugin'), function (card) {
				var name = (card.getAttribute('data-name') || '').toLowerCase();
				var active = card.getAttribute('data-active') === '1';
				var matchSearch = !q || name.indexOf(q) !== -1;
				var matchFilter = (filter === 'all') || (filter === 'active' && active) || (filter === 'addons' && !active);
				var show = matchSearch && matchFilter;
				card.style.display = show ? '' : 'none';
				if (show) { shown++; }
			});
			if (emptyEl) { if (shown === 0) { emptyEl.removeAttribute('hidden'); } else { emptyEl.setAttribute('hidden', ''); } }
		}
		if (searchEl) { searchEl.addEventListener('input', applyFilter); }
		Array.prototype.forEach.call(root.querySelectorAll('.zui-set-lic-eco__filter'), function (btn) {
			btn.addEventListener('click', function () {
				filter = btn.getAttribute('data-filter');
				Array.prototype.forEach.call(root.querySelectorAll('.zui-set-lic-eco__filter'), function (b) {
					b.classList.toggle('is-active', b === btn);
				});
				applyFilter();
			});
		});

		/* ---------- Live "secure connection" clock ---------- */
		var timeEl = document.getElementById('ast-lic-time');
		if (timeEl) {
			var tick = function () { timeEl.textContent = '(' + new Date().toLocaleTimeString() + ')'; };
			tick();
			setInterval(tick, 5000);
		}
	});
})();


/**
 * AST PRO - New Settings UI: single-page tab switching (CSS show/hide, zero fetch).
 *
 * Every tab is rendered into its own .zui-set-tab-panel on load (layout-app.php), so
 * clicking a top tab just shows that panel and hides the rest - instant, no reload, no
 * loading. All tab JS modules already initialised on load (every panel is in the DOM).
 * External links (e.g. Unfulfilled Orders) + modified clicks fall through to normal nav.
 */
(function () {
	'use strict';

	function init() {
		var app = document.getElementById('ast-settings-app');
		if (!app) { return; }

		var PAGE = 'page=woocommerce-advanced-shipment-tracking';

		function tabOf(url) { var m = String(url).match(/[?&]tab=([^&#]+)/); return m ? decodeURIComponent(m[1]) : 'settings'; }

		function setActive(tab) {
			Array.prototype.forEach.call(app.querySelectorAll('.zui-set-nav__item'), function (a) {
				var aTab = (a.getAttribute('href') || '').match(/[?&]tab=([^&#]+)/);
				var on = !!aTab && decodeURIComponent(aTab[1]) === tab;
				a.classList.toggle('is-active', on);
				if (on) { a.setAttribute('aria-current', 'page'); } else { a.removeAttribute('aria-current'); }
			});
		}

		function show(tab) {
			var found = false;
			Array.prototype.forEach.call(app.querySelectorAll('.zui-set-tab-panel'), function (p) {
				var on = p.getAttribute('data-tab') === tab;
				if (on) { p.removeAttribute('hidden'); found = true; } else { p.setAttribute('hidden', ''); }
			});
			return found;
		}

		app.addEventListener('click', function (e) {
			var a = e.target.closest('.zui-set-nav__item');
			if (!a || !app.contains(a)) { return; }
			var href = a.getAttribute('href') || '';
			if (href.indexOf(PAGE) === -1) { return; }            // external link -> normal nav
			if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) { return; }
			var tab = tabOf(a.href);
			e.preventDefault();
			if (show(tab)) {
				setActive(tab);
				window.history.pushState({ ast: 1 }, '', a.href);
				window.scrollTo(0, 0);
			} else {
				window.location.href = a.href; // no panel for this tab -> fall back to nav
			}
		});

		window.addEventListener('popstate', function () {
			if (window.location.href.indexOf(PAGE) === -1) { return; }
			var tab = tabOf(window.location.href);
			if (show(tab)) { setActive(tab); }
		});
	}

	if (document.readyState !== 'loading') { init(); }
	else { document.addEventListener('DOMContentLoaded', init); }
})();

/**
 * AST PRO - Fulfillment Dashboard: sub-tab switching (Unfulfilled / Recently Fulfilled).
 *
 * The two DataTables sections stay exactly as fulfillment.js renders them; this just
 * shows the active one and tells DataTables to recalc column widths (a table initialised
 * while hidden mis-measures its columns).
 */
(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState !== 'loading') { fn(); }
		else { document.addEventListener('DOMContentLoaded', fn); }
	}

	ready(function () {
		var root = document.getElementById('ast-set-fd');
		if (!root) { return; }

		var tabs = root.querySelectorAll('.zui-set-fd-tab');
		var crumb = document.getElementById('ast-fd-crumb');

		function adjustTables() {
			if (!(window.jQuery && jQuery.fn && jQuery.fn.dataTable)) { return; }
			['#fullfilments_table', '#fulfilled_order_table'].forEach(function (sel) {
				try {
					if (jQuery.fn.dataTable.isDataTable(sel)) { jQuery(sel).DataTable().columns.adjust(); }
				} catch (e) { /* not ready yet */ }
			});
		}

		function showSection(id) {
			Array.prototype.forEach.call(root.querySelectorAll('.zui-set-fd-body .tab_section'), function (s) {
				s.classList.toggle('is-fd-shown', s.id === id);
			});
			setTimeout(adjustTables, 30);
		}

		/* ---- Tab count badges: read the real total record count from DataTables ---- */
		function setBadge(id, n) {
			var el = document.getElementById(id);
			if (!el) { return; }
			el.textContent = n;
			if (n > 0) { el.removeAttribute('hidden'); } else { el.setAttribute('hidden', ''); }
		}

		function updateCounts() {
			if (!(window.jQuery && jQuery.fn && jQuery.fn.dataTable)) { return; }
			var map = {
				'#fullfilments_table': 'ast-fd-count-unfulfilled'
			};
			Object.keys(map).forEach(function (sel) {
				try {
					if (jQuery.fn.dataTable.isDataTable(sel)) {
						var info = jQuery(sel).DataTable().page.info();
						setBadge(map[sel], info.recordsTotal);
					}
				} catch (e) { /* not ready yet */ }
			});
		}

		if (window.jQuery) {
			jQuery(document).on('draw.dt', '#fullfilments_table', updateCounts);
		}

		showSection('unfulfilled_orders_content');

		Array.prototype.forEach.call(tabs, function (btn) {
			btn.addEventListener('click', function () {
				Array.prototype.forEach.call(tabs, function (b) { b.classList.toggle('is-active', b === btn); });
				showSection(btn.getAttribute('data-fdtab'));
				if (crumb) { crumb.textContent = btn.getAttribute('data-crumb') || ''; }
			});
		});
	});
})();

/**
 * AST PRO - CSV Import tab: Manual / SFTP-FTP Automation sub-tab toggle + isolated FTP save.
 *
 * The Automated panel posts ONLY the FTP fields to ast_csv_ftp_settings_save (which never
 * touches the other settings sections). Test-connection keeps using ftp_data_settings.
 */
(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState !== 'loading') { fn(); }
		else { document.addEventListener('DOMContentLoaded', fn); }
	}

	function initCsv() {
		var root = document.getElementById('ast-set-csv');
		if (!root || root.dataset.csvInit) { return; }
		root.dataset.csvInit = '1';

		var tabs = root.querySelectorAll('.zui-segmented__item');
		var subs = root.querySelectorAll('.zui-set-csv-sub');

		Array.prototype.forEach.call(tabs, function (btn) {
			btn.addEventListener('click', function () {
				var key = btn.getAttribute('data-csvtab');  // module-specific JS hook preserved
				Array.prototype.forEach.call(tabs, function (b) {
					var on = b === btn;
					b.classList.toggle('is-active', on);
					b.setAttribute('aria-selected', on ? 'true' : 'false');
				});
				Array.prototype.forEach.call(subs, function (s) {
					if (s.getAttribute('data-csvsub') === key) { s.removeAttribute('hidden'); }
					else { s.setAttribute('hidden', ''); }
				});
			});
		});

		var ftpForm = document.getElementById('ast_csv_ftp_form');
		if (ftpForm) {
			ftpForm.addEventListener('submit', function (e) {
				e.preventDefault();
				var ajaxurl = window.ajaxurl || (window.location.origin + '/wp-admin/admin-ajax.php');
				var btn = ftpForm.querySelector('.zui-csv-ftp-save');
				if (btn) { btn.classList.add('is-busy'); btn.disabled = true; }
				fetch(ajaxurl, { method: 'POST', credentials: 'same-origin', body: new FormData(ftpForm) })
					.then(function (r) { return r.json(); })
					.then(function (res) {
						var ok = !!(res && res.success);
						var msg = (res && res.data && res.data.message) ? res.data.message : (ok ? 'Settings saved' : 'Save failed');
						if (window.jQuery && jQuery(document).ast_snackbar) { jQuery(document).ast_snackbar(msg); }
					})
					.catch(function () {
						if (window.jQuery && jQuery(document).ast_snackbar) { jQuery(document).ast_snackbar('Save failed'); }
					})
					.then(function () {
						if (btn) { btn.classList.remove('is-busy'); btn.disabled = false; }
					});
			});
		}
	}

	ready(initCsv);
	document.addEventListener('ast:tabswapped', initCsv);
})();

/**
 * AST PRO - Bulk Paste tab: 4-step wizard (paste -> map -> importing -> done).
 *
 * Parses pasted CSV-style text client-side, builds the column-mapping UI, then applies each
 * mapped row by POSTing to the EXISTING `wc_ast_upload_csv_form_update` endpoint (same proven
 * per-row tracking-add the CSV importer uses; returns <li class="success|...error">).
 */
(function () {
	'use strict';

	function ready(fn) {
		if (document.readyState !== 'loading') { fn(); }
		else { document.addEventListener('DOMContentLoaded', fn); }
	}

	var ATTRS = [
		{ key: 'order_id', label: 'order_id', desc: 'Unique order reference (required match key)', required: true, kws: ['order_id', 'order', 'id', 'num', 'number'] },
		{ key: 'tracking_provider', label: 'tracking_provider', desc: 'Shipping carrier / courier name', required: false, kws: ['tracking_provider', 'provider', 'courier', 'carrier', 'shipping'] },
		{ key: 'tracking_number', label: 'tracking_number', desc: 'Carrier shipment tracking number', required: false, kws: ['tracking_number', 'tracking', 'track', 'code', 'number'] },
		{ key: 'date_shipped', label: 'date_shipped', desc: 'Dispatch date (DD-MM-YYYY)', required: false, kws: ['date_shipped', 'date', 'shipped'] },
		{ key: 'status_shipped', label: 'status_shipped', desc: 'Shipped status (e.g. Completed)', required: false, kws: ['status_shipped', 'status'] },
		{ key: 'shipping_note', label: 'shipping_note', desc: 'Custom note appended to the order', required: false, kws: ['shipping_note', 'note', 'comment', 'desc', 'message'] }
	];

	function initBp() {
		var root = document.getElementById('ast-set-bp');
		if (!root || root.dataset.bpInit) { return; }
		root.dataset.bpInit = '1';

		var ajaxurl = window.ajaxurl || (window.location.origin + '/wp-admin/admin-ajax.php');
		var ta = document.getElementById('ast-bp-textarea');
		var state = { headers: [], rows: [] };

		var $ = function (sel) { return root.querySelector(sel); };
		var steps = root.querySelectorAll('.zui-set-bp-step');
		var stepLis = root.querySelectorAll('#ast-bp-steps li');

		function countRows() {
			return ta.value.split(/\r?\n/).filter(function (l) { return l.trim().length > 0; }).length;
		}
		function refreshRowCount() { var el = $('#ast-bp-rowcount'); if (el) { el.textContent = countRows(); } }

		function showStep(name) {
			Array.prototype.forEach.call(steps, function (s) {
				if (s.getAttribute('data-bpstep') === name) { s.removeAttribute('hidden'); } else { s.setAttribute('hidden', ''); }
			});
			var idx = { paste: 1, map: 2, importing: 2, done: 3 }[name] || 1;
			Array.prototype.forEach.call(stepLis, function (li, i) { li.classList.toggle('active', (i + 1) <= idx); });
		}

		function splitLine(line) {
			return line.split(',').map(function (c) {
				var t = c.trim();
				if ((t.startsWith('"') && t.endsWith('"')) || (t.startsWith("'") && t.endsWith("'"))) { t = t.substring(1, t.length - 1).trim(); }
				return t;
			});
		}

		function parse() {
			var lines = ta.value.split(/\r?\n/).map(function (l) { return l.trim(); }).filter(function (l) { return l.length > 0; });
			if (!lines.length) { window.alert('Please paste some tracking rows first, or load the sample.'); return; }

			var split = lines.map(splitLine);
			var maxCols = Math.max.apply(null, split.map(function (r) { return r.length; }));
			var hasHeaders = $('#ast-bp-headers').checked;
			var headers, rows;
			if (hasHeaders) {
				headers = split[0].map(function (h, i) { return h || ('Column ' + (i + 1)); });
				rows = split.slice(1);
			} else {
				headers = []; for (var i = 0; i < maxCols; i++) { headers.push('Column ' + (i + 1)); }
				rows = split;
			}
			if (!headers.length || !rows.length) { window.alert('Parsing failed — please check your pasted rows.'); return; }

			state.headers = headers;
			state.rows = rows;

			var detected = $('#ast-bp-detected'); if (detected) { detected.textContent = rows.length; }
			buildMap(headers, hasHeaders);
			showStep('map');
		}

		function autoMatch(attr, headers, hasHeaders, posIndex) {
			for (var i = 0; i < headers.length; i++) {
				var h = headers[i].toLowerCase();
				for (var k = 0; k < attr.kws.length; k++) {
					if (h === attr.kws[k] || h.indexOf(attr.kws[k]) !== -1) { return headers[i]; }
				}
			}
			if (posIndex < headers.length && (attr.required || !hasHeaders)) { return headers[posIndex]; }
			return '';
		}

		function buildMap(headers, hasHeaders) {
			var body = $('#ast-bp-map-body');
			if (!body) { return; }
			body.innerHTML = '';
			ATTRS.forEach(function (attr, pos) {
				var selected = autoMatch(attr, headers, hasHeaders, pos);
				var row = document.createElement('div');
				row.className = 'ast-set-bp-maprow';

				var opts = '<option value="">-- Ignore this column --</option>';
				headers.forEach(function (h) { opts += '<option value="' + h.replace(/"/g, '&quot;') + '"' + (h === selected ? ' selected' : '') + '>' + escapeHtml(h) + '</option>'; });

				var prev = '';
				var idx = headers.indexOf(selected);
				if (idx !== -1 && state.rows[0] && state.rows[0][idx]) { prev = '<div class="ast-set-bp-maprow__prev">preview: "' + escapeHtml(state.rows[0][idx]) + '"</div>'; }

				row.innerHTML =
					'<div class="ast-set-bp-maprow__label"><span class="ast-set-bp-maprow__name">' + escapeHtml(attr.label) +
					(attr.required ? ' <span class="ast-set-bp-req">Required</span>' : '') + '</span>' +
					'<span class="ast-set-bp-maprow__desc">' + escapeHtml(attr.desc) + '</span></div>' +
					'<div class="ast-set-bp-maprow__dir"><span>&larr; loads from</span></div>' +
					'<div class="ast-set-bp-maprow__sel"><div class="ast-set-select-wrap"><select class="ast-set-select" data-bpkey="' + attr.key + '">' + opts + '</select></div>' + prev + '</div>';
				body.appendChild(row);
			});
		}

		function escapeHtml(s) {
			return String(s).replace(/[&<>"']/g, function (c) { return ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c]; });
		}

		function getMappings() {
			var map = {};
			root.querySelectorAll('#ast-bp-map-body select[data-bpkey]').forEach(function (sel) { map[sel.getAttribute('data-bpkey')] = sel.value; });
			return map;
		}

		function cellValue(row, header) {
			if (!header) { return ''; }
			var idx = state.headers.indexOf(header);
			return (idx !== -1 && idx < row.length) ? row[idx] : '';
		}

		function applyRow(row, map, replace) {
			var body = new FormData();
			body.append('action', 'wc_ast_upload_csv_form_update');
			body.append('order_id', cellValue(row, map.order_id));
			body.append('tracking_provider', cellValue(row, map.tracking_provider));
			body.append('tracking_number', cellValue(row, map.tracking_number));
			body.append('date_shipped', cellValue(row, map.date_shipped));
			body.append('status_shipped', cellValue(row, map.status_shipped));
			body.append('shipping_note', cellValue(row, map.shipping_note));
			body.append('replace_tracking_info', replace ? '1' : '');
			body.append('date_format_for_csv_import', (root.dataset.dateformat || 'd-m-Y'));
			return fetch(ajaxurl, { method: 'POST', credentials: 'same-origin', body: body })
				.then(function (r) { return r.text(); })
				.then(function (html) {
					var ok = /class="success"/.test(html);
					var tmp = document.createElement('div'); tmp.innerHTML = html;
					var text = (tmp.textContent || '').trim() || (ok ? 'Success' : 'Failed');
					return { ok: ok, text: text };
				})
				.catch(function () { return { ok: false, text: 'Failed - network error' }; });
		}

		function logItem(ul, res) {
			var li = document.createElement('li');
			li.className = res.ok ? 'ast-bp-li ast-bp-li--ok' : 'ast-bp-li ast-bp-li--fail';
			li.innerHTML = '<span class="ast-bp-li__mark">' + (res.ok ? '●' : '▲') + '</span><span>' + escapeHtml(res.text) + '</span>';
			ul.appendChild(li);
			ul.scrollTop = ul.scrollHeight;
		}

		function run() {
			var map = getMappings();
			if (!map.order_id) { window.alert('The order_id column mapping is required.'); return; }
			var replace = $('#ast-bp-replace').checked;
			var rows = state.rows;
			var log = $('#ast-bp-log');
			var bar = root.querySelector('.zui-bp-bar');
			var num = root.querySelector('.zui-bp-progress-num');
			if (log) { log.innerHTML = ''; }
			showStep('importing');

			var ok = 0, fail = 0, i = 0;
			function next() {
				if (i >= rows.length) { finish(ok, fail); return; }
				applyRow(rows[i], map, replace).then(function (res) {
					if (res.ok) { ok++; } else { fail++; }
					if (log) { logItem(log, res); }
					i++;
					var pct = Math.round((i / rows.length) * 100);
					if (bar) { bar.style.width = pct + '%'; }
					if (num) { num.textContent = pct + '%'; }
					next();
				});
			}
			next();
		}

		function finish(ok, fail) {
			var okEl = root.querySelector('.zui-bp-stat-ok'); if (okEl) { okEl.textContent = ok; }
			var failEl = root.querySelector('.zui-bp-stat-fail'); if (failEl) { failEl.textContent = fail; }
			var report = $('#ast-bp-report');
			var src = $('#ast-bp-log');
			if (report && src) { report.innerHTML = src.innerHTML; }
			showStep('done');
		}

		function reset() {
			ta.value = '';
			state.headers = []; state.rows = [];
			refreshRowCount();
			showStep('paste');
		}

		/* ---- Wire events ---- */
		ta.addEventListener('input', refreshRowCount);
		var sampleBtn = $('#ast-bp-sample');
		if (sampleBtn) {
			sampleBtn.addEventListener('click', function () {
				ta.value = 'order_id,tracking_provider,tracking_number,date_shipped,status_shipped,shipping_note\n' +
					'#18402,USPS,94001102008823121544,02-06-2026,Completed,Shipped from East warehouse\n' +
					'#18401,FedEx,781299388123,02-06-2026,Completed,Fragile packaging\n' +
					'#18398,UPS,1Z9E35W60310239123,31-05-2026,Completed,Dispatched standard ground';
				$('#ast-bp-headers').checked = true;
				refreshRowCount();
			});
		}
		var clearBtn = $('#ast-bp-clear'); if (clearBtn) { clearBtn.addEventListener('click', reset); }
		var parseBtn = $('#ast-bp-parse'); if (parseBtn) { parseBtn.addEventListener('click', parse); }
		var backBtn = $('#ast-bp-back'); if (backBtn) { backBtn.addEventListener('click', function () { showStep('paste'); }); }
		var runBtn = $('#ast-bp-run'); if (runBtn) { runBtn.addEventListener('click', run); }
		var againBtn = $('#ast-bp-again'); if (againBtn) { againBtn.addEventListener('click', reset); }

		refreshRowCount();
		showStep('paste');
	}

	ready(initBp);
	document.addEventListener('ast:tabswapped', initBp);
})();

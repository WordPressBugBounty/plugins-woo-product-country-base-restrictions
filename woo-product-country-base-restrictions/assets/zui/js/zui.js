/*!
 * Zorem UI — JS bundle
 * Vanilla JS, no jQuery. Exposes window.ZUI.
 *
 * Auto-init runs on DOMContentLoaded and wires every element that
 * carries a data-zui-* attribute. After an AJAX swap, call
 * ZUI.scan(rootEl) so the scanner picks up newly inserted markup.
 *
 * v0.1.0
 */
( function () {
	'use strict';

	if ( window.ZUI ) { return; }

	/* ----------------------------------------------------------------
	 * Public API
	 * ---------------------------------------------------------------- */

	var ZUI = {

		scan: function ( root ) {
			root = root || document;
			this._wireModalTriggers( root );
			this._wireSlideoutTriggers( root );
			this._wireActionsMenus( root );
			this._wireFilterForms( root );
			this._wireDropzones( root );
			this._wireSortableTables( root );
			this._wireSegmentedTabs( root );
			this._wireShell( root );
		},

		snackbar: function ( message, opts ) {
			opts = opts || {};
			var type = opts.type || 'info';
			var duration = opts.duration || 3000;

			// Variant icon paths — checkmark / cross / warning / info — all
			// inline so the JS bundle stays self-contained (no SVG sprite).
			var ICONS = {
				success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>',
				error:   '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
				warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9"  x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
				info:    '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8"  x2="12.01" y2="8"/></svg>'
			};
			ICONS.danger = ICONS.error;

			// Stack container — created once per page; lives inside .zui-scope
			// so the --zui-* tokens resolve. Falls back to <body>.
			var host = document.querySelector( '.zui-scope' ) || document.body;
			var stack = host.querySelector( ':scope > .zui-snackbar-stack' );
			if ( ! stack ) {
				stack = document.createElement( 'div' );
				stack.className = 'zui-snackbar-stack';
				host.appendChild( stack );
			}

			var el = document.createElement( 'div' );
			el.className = 'zui-snackbar zui-snackbar--' + type;
			// Feed the CSS progress-bar animation duration.
			el.style.setProperty( '--zui-snackbar-duration', duration + 'ms' );

			// Icon circle.
			var icon = document.createElement( 'span' );
			icon.className = 'zui-snackbar__icon';
			icon.innerHTML = ICONS[ type ] || ICONS.info;
			el.appendChild( icon );

			// Message text.
			var text = document.createElement( 'span' );
			text.className = 'zui-snackbar__text';
			// Preserve legacy HTML support (ast_snackbar_warning passed .html()).
			if ( opts.html ) { text.innerHTML = message; }
			else { text.textContent = message; }
			el.appendChild( text );

			// Close button — dismisses immediately.
			var close = document.createElement( 'button' );
			close.type = 'button';
			close.className = 'zui-snackbar__close';
			close.setAttribute( 'aria-label', opts.closeLabel || 'Dismiss' );
			close.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>';

			// Bottom progress bar (countdown).
			var progress = document.createElement( 'span' );
			progress.className = 'zui-snackbar__progress';

			var dismissed = false;
			function dismiss() {
				if ( dismissed ) { return; }
				dismissed = true;
				el.classList.add( 'is-leaving' );
				window.setTimeout( function () { el.remove(); }, 260 );
			}

			close.addEventListener( 'click', dismiss );
			el.appendChild( close );
			el.appendChild( progress );

			stack.appendChild( el );

			var timer = window.setTimeout( dismiss, duration );
			// Pause auto-dismiss on hover (matches the CSS progress-bar pause).
			el.addEventListener( 'mouseenter', function () { window.clearTimeout( timer ); } );
			el.addEventListener( 'mouseleave', function () { timer = window.setTimeout( dismiss, duration ); } );

			return el;
		},

		modal: {
			open:  function ( id ) { _toggleOverlay( id, true,  'zui-modal-open' ); },
			close: function ( id ) { _toggleOverlay( id, false, 'zui-modal-open' ); },
		},

		slideout: {
			open:  function ( id ) { _toggleOverlay( id, true,  'zui-slideout-open' ); },
			close: function ( id ) { _toggleOverlay( id, false, 'zui-slideout-open' ); },
		},

		/**
		 * Show or hide every `[data-zui-sidebar-tabs]` element based on whether
		 * the current tab slug is in its allowed list. Use this when only some
		 * tabs render a sidebar — the menu toggle is meaningless on tabs that
		 * don't. The attribute is a comma-separated list of tab slugs that
		 * should keep the element visible; anything else gets `hidden`.
		 *
		 * Auto-fires on the `zui:tabswapped` document event. Plugins whose tab
		 * mechanism emits a different event name (e.g. `ast:tabswapped`) can
		 * call this method directly from their own swap handler.
		 */
		syncSidebarToggles: function ( tab ) {
			tab = String( tab || '' );
			var nodes = document.querySelectorAll( '[data-zui-sidebar-tabs]' );
			Array.prototype.forEach.call( nodes, function ( el ) {
				var allowed = ( el.getAttribute( 'data-zui-sidebar-tabs' ) || '' )
					.split( ',' ).map( function ( s ) { return s.trim(); } );
				if ( allowed.indexOf( tab ) !== -1 ) {
					el.removeAttribute( 'hidden' );
				} else {
					el.setAttribute( 'hidden', '' );
				}
			} );
		},

		/* ------------------------------------------------------------
		 * Internal — auto-init wirings
		 * ------------------------------------------------------------ */

		_wireModalTriggers: function ( root ) {
			// [data-zui-modal-toggle="id"]  → open
			// [data-zui-modal-close]        → close ancestor modal
			_delegateClick( root, '[data-zui-modal-toggle]', function ( el ) {
				ZUI.modal.open( el.getAttribute( 'data-zui-modal-toggle' ) );
			} );
			_delegateClick( root, '[data-zui-modal-close]', function ( el, ev ) {
				var modal = el.closest( '.zui-modal' );
				if ( modal && modal.id ) { ZUI.modal.close( modal.id ); }
			} );
		},

		_wireSlideoutTriggers: function ( root ) {
			_delegateClick( root, '[data-zui-slideout-toggle]', function ( el ) {
				ZUI.slideout.open( el.getAttribute( 'data-zui-slideout-toggle' ) );
			} );
			_delegateClick( root, '[data-zui-slideout-close]', function ( el ) {
				var so = el.closest( '.zui-slideout' );
				if ( so && so.id ) { ZUI.slideout.close( so.id ); }
			} );
		},

		_wireActionsMenus: function ( root ) {
			// Kebab dropdown. Markup:
			//   .zui-actions > .zui-actions-toggle + .zui-actions-menu
			_delegateClick( root, '.zui-actions-toggle', function ( el, ev ) {
				var menu = el.parentNode.querySelector( '.zui-actions-menu' );
				if ( ! menu ) { return; }
				var open = ! menu.hidden;
				_closeAllActionsMenus();
				menu.hidden = open;
				el.setAttribute( 'aria-expanded', open ? 'false' : 'true' );
				ev.stopPropagation();
			} );
		},

		_wireFilterForms: function ( root ) {
			// [data-zui-filter-form] → auto-submit on change (debounced)
			var forms = root.querySelectorAll( '[data-zui-filter-form]' );
			forms.forEach( function ( form ) {
				if ( form._zuiBound ) { return; }
				form._zuiBound = true;
				var t;
				form.addEventListener( 'change', function () {
					window.clearTimeout( t );
					t = window.setTimeout( function () { form.submit(); }, 250 );
				} );
			} );
		},

		_wireDropzones: function ( root ) {
			// [data-zui-dropzone] → drag-and-drop file pick
			var zones = root.querySelectorAll( '[data-zui-dropzone]' );
			zones.forEach( function ( zone ) {
				if ( zone._zuiBound ) { return; }
				zone._zuiBound = true;
				var input = zone.querySelector( 'input[type="file"]' );
				if ( ! input ) { return; }

				[ 'dragenter', 'dragover' ].forEach( function ( evt ) {
					zone.addEventListener( evt, function ( e ) {
						e.preventDefault();
						zone.classList.add( 'is-dragover' );
					} );
				} );
				[ 'dragleave', 'drop' ].forEach( function ( evt ) {
					zone.addEventListener( evt, function ( e ) {
						e.preventDefault();
						zone.classList.remove( 'is-dragover' );
					} );
				} );
				zone.addEventListener( 'drop', function ( e ) {
					if ( e.dataTransfer && e.dataTransfer.files.length ) {
						input.files = e.dataTransfer.files;
						input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
					}
				} );
				zone.addEventListener( 'click', function () { input.click(); } );
			} );
		},

		_wireSortableTables: function ( root ) {
			_delegateClick( root, '.zui-table th.zui-sortable', function ( th ) {
				var cur = th.getAttribute( 'data-zui-sort-dir' );
				var next = cur === 'asc' ? 'desc' : 'asc';
				// Clear siblings, set this one
				th.parentNode.querySelectorAll( 'th.zui-sortable' ).forEach( function ( other ) {
					other.removeAttribute( 'data-zui-sort-dir' );
				} );
				th.setAttribute( 'data-zui-sort-dir', next );
				th.dispatchEvent( new CustomEvent( 'zui:sort', {
					bubbles: true,
					detail: { column: th.getAttribute( 'data-zui-sort' ), direction: next },
				} ) );
			} );
		},

		_wireSegmentedTabs: function ( root ) {
			// .zui-tabs--segmented buttons → mutual-exclusion is-active
			_delegateClick( root, '.zui-tabs--segmented .zui-tab', function ( el ) {
				var nav = el.closest( '.zui-tabs--segmented' );
				if ( ! nav ) { return; }
				nav.querySelectorAll( '.zui-tab' ).forEach( function ( t ) {
					t.classList.toggle( 'is-active', t === el );
				} );
				nav.dispatchEvent( new CustomEvent( 'zui:tab-change', {
					bubbles: true,
					detail: { tab: el.dataset.tab || el.textContent.trim() },
				} ) );
			} );
		},

		_wireShell: function ( root ) {
			// Generic admin-shell switching — top tabs, sidebar sections, and
			// the mobile drawer. Markup contract:
			//   Top tabs:  .zui-tabs__item[data-zui-tab="x"]  ↔  .zui-tab-panel[data-zui-tab="x"]
			//   Sections:  .zui-sidebar__item[data-zui-section="x"]  ↔  .zui-section[data-zui-section="x"]
			//   Drawer:    [data-zui-drawer-toggle] / [data-zui-drawer-close] → .zui-sidebar-open on .zui-scope
			// The active item carries .is-active; inactive panels carry [hidden].

			_delegateClick( root, '.zui-tabs__item[data-zui-tab]', function ( el, ev ) {
				// A real link tab (href to another page) navigates normally.
				var href = el.getAttribute( 'href' );
				if ( el.tagName === 'A' && href && href.charAt( 0 ) !== '#' ) { return; }
				ev.preventDefault();
				var key   = el.getAttribute( 'data-zui-tab' );
				var tabs  = el.closest( '.zui-tabs' );
				var scope = el.closest( '.zui-scope' ) || document;
				if ( tabs ) {
					tabs.querySelectorAll( '.zui-tabs__item[data-zui-tab]' ).forEach( function ( t ) {
						var on = t === el;
						t.classList.toggle( 'is-active', on );
						if ( on ) { t.setAttribute( 'aria-current', 'page' ); } else { t.removeAttribute( 'aria-current' ); }
					} );
				}
				scope.querySelectorAll( '.zui-tab-panel[data-zui-tab]' ).forEach( function ( p ) {
					p.hidden = p.getAttribute( 'data-zui-tab' ) !== key;
				} );
				scope.dispatchEvent( new CustomEvent( 'zui:tab', { bubbles: true, detail: { tab: key } } ) );
			} );

			_delegateClick( root, '.zui-sidebar__item[data-zui-section]', function ( el, ev ) {
				ev.preventDefault();
				var key       = el.getAttribute( 'data-zui-section' );
				var nav       = el.closest( '.zui-sidebar' );
				var container = el.closest( '.zui-layout' ) || el.closest( '.zui-tab-panel' ) || el.closest( '.zui-scope' ) || document;
				if ( nav ) {
					nav.querySelectorAll( '.zui-sidebar__item[data-zui-section]' ).forEach( function ( t ) {
						var on = t === el;
						t.classList.toggle( 'is-active', on );
						if ( on ) { t.setAttribute( 'aria-current', 'true' ); } else { t.removeAttribute( 'aria-current' ); }
					} );
				}
				container.querySelectorAll( '.zui-section[data-zui-section]' ).forEach( function ( s ) {
					var on = s.getAttribute( 'data-zui-section' ) === key;
					s.hidden = ! on;
					s.classList.toggle( 'is-active', on );
				} );
				var scope = el.closest( '.zui-scope' );
				if ( scope ) { scope.classList.remove( 'zui-sidebar-open' ); }
				( nav || document ).dispatchEvent( new CustomEvent( 'zui:section', { bubbles: true, detail: { section: key } } ) );
			} );

			_delegateClick( root, '[data-zui-drawer-toggle]', function ( el ) {
				var scope = el.closest( '.zui-scope' );
				if ( scope ) { scope.classList.toggle( 'zui-sidebar-open' ); }
			} );
			_delegateClick( root, '[data-zui-drawer-close]', function ( el ) {
				var scope = el.closest( '.zui-scope' );
				if ( scope ) { scope.classList.remove( 'zui-sidebar-open' ); }
			} );
		},
	};

	/* ----------------------------------------------------------------
	 * Helpers
	 * ---------------------------------------------------------------- */

	function _toggleOverlay ( id, show, bodyClass ) {
		var el = document.getElementById( id );
		if ( ! el ) { return; }
		if ( show ) {
			el.hidden = false;
			document.body.classList.add( bodyClass );
			// Focus first focusable element
			var focusable = el.querySelector( 'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])' );
			if ( focusable ) { focusable.focus(); }
		} else {
			el.hidden = true;
			// Only remove the body class if nothing else of that kind is open
			if ( ! document.querySelector( '.zui-modal:not([hidden]), .zui-slideout:not([hidden])' ) ) {
				document.body.classList.remove( 'zui-modal-open' );
				document.body.classList.remove( 'zui-slideout-open' );
			}
		}
	}

	function _delegateClick ( root, selector, handler ) {
		root = root || document;
		// Keep a per-root registry of (selector, handler) pairs dispatched by a
		// single click listener, so MANY selectors can be delegated on one root.
		// (The previous one-listener-per-root design silently dropped every
		// selector after the first.)
		if ( ! root._zuiClickHandlers ) {
			root._zuiClickHandlers = [];
			root.addEventListener( 'click', function ( ev ) {
				root._zuiClickHandlers.forEach( function ( pair ) {
					var el = ev.target.closest( pair.selector );
					if ( el ) { pair.handler( el, ev ); }
				} );
			} );
		}
		// Avoid registering the same selector twice when scan() re-runs.
		var dup = root._zuiClickHandlers.some( function ( p ) { return p.selector === selector; } );
		if ( dup ) { return; }
		root._zuiClickHandlers.push( { selector: selector, handler: handler } );
	}

	function _closeAllActionsMenus () {
		document.querySelectorAll( '.zui-actions-menu' ).forEach( function ( m ) {
			m.hidden = true;
			var toggle = m.parentNode.querySelector( '.zui-actions-toggle' );
			if ( toggle ) { toggle.setAttribute( 'aria-expanded', 'false' ); }
		} );
	}

	/* ----------------------------------------------------------------
	 * Global behaviour
	 * ---------------------------------------------------------------- */

	// Click outside an actions menu / modal backdrop → close
	document.addEventListener( 'click', function ( ev ) {
		// Backdrop click closes its modal
		if ( ev.target.classList && ev.target.classList.contains( 'zui-modal-backdrop' ) ) {
			var modal = ev.target.closest( '.zui-modal' );
			if ( modal && modal.id ) { ZUI.modal.close( modal.id ); }
		}
		// Click outside an open actions menu closes it
		if ( ! ev.target.closest( '.zui-actions' ) ) {
			_closeAllActionsMenus();
		}
	} );

	// Esc closes any open modal / slideout
	document.addEventListener( 'keydown', function ( ev ) {
		if ( ev.key !== 'Escape' ) { return; }
		var openModal = document.querySelector( '.zui-modal:not([hidden])' );
		if ( openModal && openModal.id ) { ZUI.modal.close( openModal.id ); return; }
		var openSlideout = document.querySelector( '.zui-slideout:not([hidden])' );
		if ( openSlideout && openSlideout.id ) { ZUI.slideout.close( openSlideout.id ); return; }
		_closeAllActionsMenus();
	} );

	/* Auto-sync sidebar-toggle visibility whenever the library tab swap fires. */
	document.addEventListener( 'zui:tabswapped', function ( e ) {
		var tab = e && e.detail && e.detail.tab ? e.detail.tab : '';
		ZUI.syncSidebarToggles( tab );
	} );

	/* ----------------------------------------------------------------
	 * Boot
	 * ---------------------------------------------------------------- */

	window.ZUI = ZUI;

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', function () { ZUI.scan(); } );
	} else {
		ZUI.scan();
	}

} )();

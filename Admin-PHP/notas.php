<script>
			function validarCampos() {
				const form = document.getElementById('myForm');
				const inputs = form.querySelectorAll('input');

				let camposVacios = false;
				inputs.forEach(input => {
					const name = input.getAttribute('name');
					if (name && !name.endsWith('2') && input.value.trim() === '') {
						camposVacios = true;
					}
				});

				if (camposVacios) {
					alert('Por favor completa todos los campos antes de guardar.');
				} else {
					copiarValores();
				}
			}
			// Función para guardar los valores en localStorage
			function saveToLocalStorage(name, value) {
				localStorage.setItem(name, value);
			}
			// Función para cargar los valores desde localStorage
			function loadFromLocalStorage(name) {
				return localStorage.getItem(name) || '';
			}
			// Función para convertir porcentaje a decimal
			function porcentajeADecimal(value) {
				return parseFloat(value) / 100;
			}
			// Función para realizar los cálculos y actualizar los campos
			function calcularValores() {
				const form = document.getElementById('myForm');
				const tlr2 = porcentajeADecimal(loadFromLocalStorage('tlr2'));
				const tlrc2 = porcentajeADecimal(loadFromLocalStorage('tlrc2'));
				const rsyp2 = porcentajeADecimal(loadFromLocalStorage('rsyp2'));
				const rlr2 = porcentajeADecimal(loadFromLocalStorage('rlr2'));
				const bd2 = parseFloat(loadFromLocalStorage('bd2'));
				const tdi2 = porcentajeADecimal(loadFromLocalStorage('tdi2'));
				const da2 = porcentajeADecimal(loadFromLocalStorage('da2'));
				const a2 = porcentajeADecimal(loadFromLocalStorage('a2'));
				const tldrct2 = porcentajeADecimal(loadFromLocalStorage('tldrct2'));
				const ppt2 = porcentajeADecimal(loadFromLocalStorage('ppt2'));
				const cdadi2 = porcentajeADecimal(loadFromLocalStorage('cdadi2'));
				const sda2 = porcentajeADecimal(loadFromLocalStorage('sda2'));
				// Cálculo de rp2
				const rp2 = tlrc2 - tlr2;
				// Cálculo de pdm2
				const pdm2 = rsyp2 - rlr2;
				// Cálculo de d2 (convertir a2 de porcentaje a decimal)
				const d2 = da2 * a2;
				// Cálculo de p2
				const p2 = a2 - d2;
				// Cálculo de dp2
				const dp2 = d2 / p2;
				// Cálculo de pa2
				const pa2 = p2 / a2;
				// Cálculo de ba2
				const ba2 = bd2 * (1 + (dp2 * (1 - tdi2)));
				// Cálculo de cepi2
				const cepi2 = tlr2 + ba2 * pdm2 + rp2;
				// Cálculo de tldrcy2 (igual a tlrc2)
				const tldrcy2 = tlrc2;
				// Cálculo de de2
				const de2 = (1 + tldrct2) / (1 + tldrcy2) - 1;
				// Cálculo de cepiep2
				const cepiep2 = (1 + cepi2) * (1 + de2) - 1;
				// Cálculo de cepeiep2
				const cepeiep2 = cepiep2 + ppt2;
				// Cálculo de cdddi2
				const cdddi2 = cdadi2 * (1 - tdi2);
				// Cálculo de tdd2
				const tdd2 = pa2 * cepeiep2 + da2 * cdddi2;
				// Cálculo de tddaar2
				const tddaar2 = tdd2 + sda2;
				// Actualizar los valores en el formulario
				form.querySelector('input[name="rp2"]').value = (rp2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="pdm2"]').value = (pdm2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="d2"]').value = d2.toFixed(2);
				form.querySelector('input[name="p2"]').value = p2.toFixed(2);
				form.querySelector('input[name="dp2"]').value = dp2.toFixed(2);
				form.querySelector('input[name="pa2"]').value = pa2.toFixed(2);
				form.querySelector('input[name="ba2"]').value = ba2.toFixed(2);
				form.querySelector('input[name="cepi2"]').value = (cepi2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="tldrcy2"]').value = (tldrcy2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="de2"]').value = (de2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="cepiep2"]').value = (cepiep2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="cepeiep2"]').value = (cepeiep2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="cdddi2"]').value = (cdddi2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="tdd2"]').value = (tdd2 * 100).toFixed(2) + '%';
				form.querySelector('input[name="tddaar2"]').value = (tddaar2 * 100).toFixed(2) + '%';
			}
			// Función para copiar los valores y guardarlos en localStorage
			function copiarValores() {
				const form = document.getElementById('myForm');
				const inputs = form.querySelectorAll('input');
				inputs.forEach(input => {
					const name = input.getAttribute('name');
					if (name && !name.endsWith('2')) {
						const value = input.value;
						saveToLocalStorage(name, value);
						const readOnlyInput = form.querySelector(`input[name="${name}2"]`);
						if (readOnlyInput) {
							readOnlyInput.value = value;
							saveToLocalStorage(`${name}2`, value);
						}
						// Vaciar los campos de entrada después de guardar
						input.value = '';
					}
				});
				calcularValores();
			}
			// Cargar los valores al cargar la página
			window.onload = function() {
				const form = document.getElementById('myForm');
				const inputs = form.querySelectorAll('input');

				inputs.forEach(input => {
					const name = input.getAttribute('name');
					if (name) {
						const value = loadFromLocalStorage(name);
						if (value) {
							input.value = value;
						}
					}
				});
				calcularValores();
			};
		</script>
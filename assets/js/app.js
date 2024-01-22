document.addEventListener("DOMContentLoaded", () => {
	const registrationForm = document.getElementById("registration-form");
	const loginForm = document.getElementById("login-form");
	const dashboard = document.getElementById("dashboard");

	const setCookie = (name, value, days) => {
		let expires = "";
		if (days) {
			const date = new Date();
			date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
			expires = `; expires=${date.toUTCString()}`;
		}

		const isLocalhost = window.location.origin.includes("localhost");
		const secureAttribute =
			window.location.protocol === "https:" ? "; secure" : "";
		const httpOnlyAttribute = isLocalhost ? "" : "; httponly";

		document.cookie = `${name}=${value}${expires}; path=/; samesite=strict${secureAttribute}${httpOnlyAttribute}`;
	};

	const getCookie = (name) => {
		const cookieName = name + "=";
		const cookiesArray = document.cookie.split(";");
		for (let i = 0; i < cookiesArray.length; i++) {
			let currentCookie = cookiesArray[i];
			while (currentCookie.charAt(0) == " ")
				currentCookie = currentCookie.substring(
					1,
					currentCookie.length
				);
			if (currentCookie.indexOf(cookieName) == 0)
				return currentCookie.substring(
					cookieName.length,
					currentCookie.length
				);
		}
		return null;
	};

	const deleteCookie = (name) => {
		document.cookie =
			name + "=; expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/";
	};

	function logoutUser() {
		deleteCookie("token");
		deleteCookie("firstName");
		deleteCookie("lastName");

		window.location.href = "./login";
	}

	if (registrationForm) {
		const errorBlock = registrationForm.querySelector(".show-form-error");
		const submitButton = registrationForm.querySelector("#submit-button");

		const validateInputs = (e) => {
			const { name, value } = e.target;
			const sanitizedName = value.replace(/[^a-zA-Z\s]/g, "");
			let isFormValid = false;

			switch (name) {
				case "firstName":
				case "lastName":
					if (value !== sanitizedName) {
						e.target.value = sanitizedName;
						isFormValid = true;
					}
					break;

				case "userName":
					const cleanedUsername = value.replace(/[^a-zA-Z0-9_]/g, "");

					if (cleanedUsername.length > 0) {
						isFormValid = true;
						errorBlock.classList.remove("d-block");
						errorBlock.classList.add("d-none");
					} else {
						errorBlock.innerHTML =
							"Username can only contain letters, numbers, and underscores (no spaces)";
						errorBlock.classList.remove("d-none");
						errorBlock.classList.add("d-block");
					}

					e.target.value = cleanedUsername;
					break;

				case "email":
					const sanitizedEmail = value.replace(
						/[^a-zA-Z0-9@._-]/g,
						""
					);
					if (value !== sanitizedEmail) {
						e.target.value = sanitizedEmail;
						isFormValid = true;
					}
					break;

				case "phone":
					const sanitizedPhone = value.replace(/[^0-9]/g, "");
					const formattedPhone = sanitizedPhone.slice(0, 10);
					if (value !== formattedPhone) {
						e.target.value = formattedPhone;
						isFormValid = true;
					}
					break;

				case "password":
					if (value.length < 6) {
						errorBlock.innerHTML =
							"Password should be atlease 6 characters long";
						errorBlock.classList.remove("d-none");
						errorBlock.classList.add("d-block");
					} else {
						errorBlock.classList.remove("d-block");
						errorBlock.classList.add("d-none");
						isFormValid = true;
					}
					break;

				case "confirmPassword":
					const password =
						registrationForm.querySelector(
							'[name="password"]'
						).value;
					const confirmPassword = registrationForm.querySelector(
						'[name="confirmPassword"]'
					).value;

					if (password !== confirmPassword) {
						errorBlock.innerHTML =
							"Password and confirm password should be same";
						errorBlock.classList.remove("d-none");
						errorBlock.classList.add("d-block");
					} else {
						errorBlock.classList.remove("d-block");
						errorBlock.classList.add("d-none");
						isFormValid = true;
					}
					break;

				default:
					break;
			}

			const allFieldsValid = Array.from(
				document.querySelectorAll(".form-control")
			).every((field) => field.checkValidity());

			submitButton.disabled = !(isFormValid && allFieldsValid);
		};

		document.querySelectorAll(".form-control").forEach((element, index) => {
			element.addEventListener("input", validateInputs);
		});

		registrationForm.addEventListener("submit", (e) => {
			e.preventDefault();

			const formData = new FormData(e.target);
			const payload = JSON.stringify({
				payload: btoa(JSON.stringify(Object.fromEntries(formData))),
			});
			const loader = document.getElementById("loader");

			try {
				loader.classList.remove("d-none");

				fetch("./api/register", {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
					},
					body: payload,
				})
					.then((response) => response.json())
					.then((data) => {
						if (data.status === 200 && data.message == "Success") {
							alert("Registered successfully, please login");
							window.location.href = "./login";
						} else {
							errorBlock.innerHTML = data.message;
							errorBlock.classList.remove("d-none");
							errorBlock.classList.add("d-block");
						}
					})
					.finally(() => loader.classList.add("d-none"));
			} catch (e) {
				console.log(e);
			}
		});
	}

	if (loginForm) {
		const token = getCookie("token");

		if (token) {
			window.location.href = "./";
			return;
		}

		const errorBlock = loginForm.querySelector(".show-form-error");
		const submitButton = loginForm.querySelector("#submit-button");

		const validateInputs = (e) => {
			const { name, value } = e.target;
			let isFormValid = false;

			switch (name) {
				case "userName":
					const cleanedUsername = value.replace(/[^a-zA-Z0-9_]/g, "");

					if (cleanedUsername.length > 0) {
						isFormValid = true;
						errorBlock.classList.remove("d-block");
						errorBlock.classList.add("d-none");
					} else {
						errorBlock.innerHTML =
							"Please enter a valid username (Only letters, numbers, and underscores with no spaces are allowed)";
						errorBlock.classList.remove("d-none");
						errorBlock.classList.add("d-block");
					}

					e.target.value = cleanedUsername;
					break;

				case "password":
					if (value.length < 6) {
						errorBlock.innerHTML = "Please enter a valid password";
						errorBlock.classList.remove("d-none");
						errorBlock.classList.add("d-block");
					} else {
						errorBlock.classList.remove("d-block");
						errorBlock.classList.add("d-none");
						isFormValid = true;
					}
					break;

				default:
					break;
			}

			const allFieldsValid = Array.from(
				document.querySelectorAll(".form-control")
			).every((field) => field.checkValidity());

			submitButton.disabled = !(isFormValid && allFieldsValid);
		};

		document.querySelectorAll(".form-control").forEach((element, index) => {
			element.addEventListener("input", validateInputs);
		});

		loginForm.addEventListener("submit", (e) => {
			e.preventDefault();

			const formData = new FormData(e.target);
			const payload = JSON.stringify({
				payload: btoa(JSON.stringify(Object.fromEntries(formData))),
			});
			const loader = document.getElementById("loader");

			try {
				loader.classList.remove("d-none");

				fetch("./api/login", {
					method: "POST",
					headers: {
						"Content-Type": "application/json",
					},
					body: payload,
				})
					.then((response) => response.json())
					.then((data) => {
						if (data.status === 200 && data.message == "Success") {
							const userData = data.data;

							setCookie("token", userData.token, 7);
							setCookie("firstName", userData.firstName, 7);
							setCookie("lastName", userData.lastName, 7);

							alert("Logged in successfully");
							window.location.href = "./";
						} else {
							errorBlock.innerHTML = data.message;
							errorBlock.classList.remove("d-none");
							errorBlock.classList.add("d-block");
						}
					})
					.finally(() => loader.classList.add("d-none"));
			} catch (e) {
				console.log(e);
			}
		});
	}

	if (dashboard) {
		const token = getCookie("token");
		const logoutButton = dashboard.querySelector("#logout-btn");

		if (!token) {
			logoutUser();
			return;
		}

		const payload = JSON.stringify({
			payload: btoa(
				JSON.stringify({
					verifyToken: true,
					token: token,
				})
			),
		});
		const loader = document.getElementById("loader");

		try {
			loader.classList.remove("d-none");

			fetch("./api/user", {
				method: "POST",
				headers: {
					"Content-Type": "application/json",
					Authorization: token,
				},
				body: payload,
			})
				.then((response) => response.json())
				.then((data) => {
					if (data.status === 200 && data.message == "Success") {
						const userData = data.data;

						document.getElementById("firstName").innerHTML =
							userData.firstName;
						document.getElementById("lastName").innerHTML =
							userData.lastName;
						document.getElementById("username").innerHTML =
							userData.username;
						document.getElementById("email").innerHTML =
							userData.email;
						document.getElementById("phone").innerHTML =
							userData.phone;
					} else {
						alert(data.message);

						if (data.status === 403) {
							logoutUser();
						}
					}
				})
				.finally(() => loader.classList.add("d-none"));
		} catch (e) {
			console.log(e);
		}

		logoutButton.addEventListener("click", (e) => {
			e.preventDefault();

			if (confirm("Are you sure you want to logout?")) {
				logoutUser();
			}
		});
	}
});

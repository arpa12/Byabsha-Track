import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { useAuth } from "../context/AuthContext";
import { useLanguage } from "../context/LanguageContext";
import "./Login.css";

const Login = () => {
  const [formData, setFormData] = useState({ email: "", password: "" });
  const [error, setError] = useState("");
  const [loading, setLoading] = useState(false);
  const [showPassword, setShowPassword] = useState(false);
  const { login } = useAuth();
  const { t, toggleLanguage, language } = useLanguage();
  const navigate = useNavigate();

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    setLoading(true);
    try {
      await login(formData);
      navigate("/dashboard");
    } catch (err) {
      setError(
        err.response?.data?.message ||
          (language === "en"
            ? "Login failed. Please try again."
            : "লগইন ব্যর্থ হয়েছে। আবার চেষ্টা করুন।"),
      );
    } finally {
      setLoading(false);
    }
  };

  const demoUsers = [
    {
      role: t("owner"),
      email: "owner@byabshatrack.com",
      icon: "👤",
      styleClass: "login-demo-btn-owner",
    },
    {
      role: t("manager"),
      email: "manager@byabshatrack.com",
      icon: "💼",
      styleClass: "login-demo-btn-manager",
    },
    {
      role: t("salesman"),
      email: "salesman@byabshatrack.com",
      icon: "🛒",
      styleClass: "login-demo-btn-salesman",
    },
  ];

  return (
    <div className="login-container">
      <div className="login-bg-pattern" />
      <div className="login-blob login-blob-1" />
      <div className="login-blob login-blob-2" />
      <div className="login-blob login-blob-3" />

      <button onClick={toggleLanguage} className="login-lang-toggle">
        <svg
          className="login-lang-icon"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth={2}
            d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"
          />
        </svg>
        <span className="login-lang-text">
          {language === "en" ? "বাংলা" : "English"}
        </span>
      </button>

      <div className="login-content">
        <div className="login-grid">
          <div className="login-branding">
            <div className="login-branding-content">
              <div className="login-branding-header">
                <div className="login-logo-wrapper">
                  <div className="login-logo">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2.5}
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                      />
                    </svg>
                  </div>
                  <h1 className="login-app-name">{t("appName")}</h1>
                </div>

                <div className="login-branding-text">
                  <h2 className="login-heading">
                    {language === "en"
                      ? "Manage Your Business Smarter"
                      : "আপনার ব্যবসা স্মার্টভাবে পরিচালনা করুন"}
                  </h2>
                  <p className="login-tagline">{t("appTagline")}</p>
                </div>
              </div>

              <div className="login-stats">
                {[
                  {
                    value: "50K+",
                    label: language === "en" ? "Transactions" : "লেনদেন",
                  },
                  {
                    value: "99.9%",
                    label: language === "en" ? "Uptime" : "আপটাইম",
                  },
                  {
                    value: "1.2K+",
                    label: language === "en" ? "Users" : "ব্যবহারকারী",
                  },
                ].map((stat, idx) => (
                  <div key={idx} className="login-stat-card">
                    <p className="login-stat-value">{stat.value}</p>
                    <p className="login-stat-label">{stat.label}</p>
                  </div>
                ))}
              </div>
            </div>
          </div>

          <div className="login-form-panel">
            <div className="login-form-card">
              <div className="login-mobile-logo">
                <div className="login-mobile-logo-icon">
                  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                      strokeLinecap="round"
                      strokeLinejoin="round"
                      strokeWidth={2.5}
                      d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                    />
                  </svg>
                </div>
                <h1 className="login-mobile-app-name">{t("appName")}</h1>
              </div>

              <button
                type="button"
                onClick={() => navigate("/")}
                className="login-back-home-btn"
              >
                <svg
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                  className="login-back-icon"
                >
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                  />
                </svg>
                {language === "en" ? "Back to Home" : "হোমে ফিরুন"}
              </button>

              <div className="login-form-header">
                <h2 className="login-form-title">{t("welcomeBack")}</h2>
                <p className="login-form-subtitle">{t("loginSubtitle")}</p>
              </div>

              <form onSubmit={handleSubmit} className="login-form">
                <div className="login-form-group">
                  <label htmlFor="email" className="login-form-label">
                    {t("email")}
                  </label>
                  <div className="login-input-wrapper">
                    <div className="login-input-icon">
                      <svg
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                        />
                      </svg>
                    </div>
                    <input
                      id="email"
                      name="email"
                      type="email"
                      required
                      className="login-input"
                      placeholder={
                        language === "en"
                          ? "owner@byabshatrack.com"
                          : "আপনার ইমেইল"
                      }
                      value={formData.email}
                      onChange={handleChange}
                    />
                  </div>
                </div>

                <div className="login-form-group">
                  <label htmlFor="password" className="login-form-label">
                    {t("password")}
                  </label>
                  <div className="login-input-wrapper">
                    <div className="login-input-icon">
                      <svg
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                        />
                      </svg>
                    </div>
                    <input
                      id="password"
                      name="password"
                      type={showPassword ? "text" : "password"}
                      required
                      className="login-input login-input-with-toggle"
                      placeholder={
                        language === "en"
                          ? "Enter your password"
                          : "আপনার পাসওয়ার্ড"
                      }
                      value={formData.password}
                      onChange={handleChange}
                    />
                    <button
                      type="button"
                      onClick={() => setShowPassword(!showPassword)}
                      className="login-password-toggle"
                    >
                      <svg
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        {showPassword ? (
                          <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth={2}
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                          />
                        ) : (
                          <>
                            <path
                              strokeLinecap="round"
                              strokeLinejoin="round"
                              strokeWidth={2}
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                            <path
                              strokeLinecap="round"
                              strokeLinejoin="round"
                              strokeWidth={2}
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                            />
                          </>
                        )}
                      </svg>
                    </button>
                  </div>
                </div>

                {error && (
                  <div className="login-error">
                    <svg
                      className="login-error-icon"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        fillRule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clipRule="evenodd"
                      />
                    </svg>
                    <p className="login-error-text">{error}</p>
                  </div>
                )}

                <button
                  type="submit"
                  disabled={loading}
                  className="login-submit-btn"
                >
                  {loading ? (
                    <>
                      <svg
                        className="login-spinner"
                        fill="none"
                        viewBox="0 0 24 24"
                      >
                        <circle
                          style={{ opacity: 0.25 }}
                          cx="12"
                          cy="12"
                          r="10"
                          stroke="currentColor"
                          strokeWidth="4"
                        />
                        <path
                          style={{ opacity: 0.75 }}
                          fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        />
                      </svg>
                      {t("signingIn")}
                    </>
                  ) : (
                    <>
                      {t("signIn")}
                      <svg
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                      >
                        <path
                          strokeLinecap="round"
                          strokeLinejoin="round"
                          strokeWidth={2}
                          d="M13 7l5 5m0 0l-5 5m5-5H6"
                        />
                      </svg>
                    </>
                  )}
                </button>
              </form>

              <div className="login-divider">
                <div className="login-divider-line" />
                <span className="login-divider-text">
                  {t("demoCredentials")}
                </span>
                <div className="login-divider-line" />
              </div>

              <div className="login-demo-section">
                {demoUsers.map((user) => (
                  <button
                    key={user.email}
                    type="button"
                    onClick={() =>
                      setFormData({ email: user.email, password: "password" })
                    }
                    className={`login-demo-btn ${user.styleClass}`}
                  >
                    <div className="login-demo-btn-content">
                      <span className="login-demo-icon">{user.icon}</span>
                      <div>
                        <p className="login-demo-role">{user.role}</p>
                        <p className="login-demo-email">{user.email}</p>
                      </div>
                    </div>
                    <span className="login-demo-password">password</span>
                  </button>
                ))}
              </div>

              <p className="login-footer">
                © 2026 {t("appName")}.{" "}
                {language === "en"
                  ? "All rights reserved."
                  : "সর্বস্বত্ব সংরক্ষিত।"}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Login;

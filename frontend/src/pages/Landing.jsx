import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useLanguage } from "../context/LanguageContext";
import "./Landing.css";

const Landing = () => {
  const { t, toggleLanguage, language } = useLanguage();
  const navigate = useNavigate();
  const [stats, setStats] = useState({
    totalSales: 0,
    totalProducts: 0,
    totalBranches: 3,
    totalUsers: 0,
  });

  useEffect(() => {
    // Animate numbers on load
    const animateValue = (start, end, duration, callback) => {
      let startTimestamp = null;
      const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        callback(Math.floor(progress * (end - start) + start));
        if (progress < 1) {
          window.requestAnimationFrame(step);
        }
      };
      window.requestAnimationFrame(step);
    };

    // Impressive demo statistics
    setTimeout(() => {
      animateValue(0, 50000, 2000, (val) => {
        setStats((prev) => ({ ...prev, totalSales: val }));
      });
      animateValue(0, 1250, 2000, (val) => {
        setStats((prev) => ({ ...prev, totalProducts: val }));
      });
      animateValue(0, 125, 2000, (val) => {
        setStats((prev) => ({ ...prev, totalUsers: val }));
      });
    }, 100);
  }, []);

  const features = [
    {
      icon: "🛒",
      title: language === "en" ? "Point of Sale" : "পয়েন্ট অফ সেল",
      description:
        language === "en"
          ? "Fast and intuitive POS system with barcode scanning, quick checkout, and invoice generation"
          : "বারকোড স্ক্যানিং, দ্রুত চেকআউট এবং ইনভয়েস তৈরি সহ দ্রুত এবং স্বজ্ঞাত POS সিস্টেম",
    },
    {
      icon: "📦",
      title:
        language === "en" ? "Inventory Management" : "ইনভেন্টরি ব্যবস্থাপনা",
      description:
        language === "en"
          ? "Track stock levels across multiple branches with automatic low stock alerts and management"
          : "স্বয়ংক্রিয় কম স্টক সতর্কতা এবং ব্যবস্থাপনা সহ একাধিক শাখায় স্টক স্তর ট্র্যাক করুন",
    },
    {
      icon: "💰",
      title: language === "en" ? "Sales Tracking" : "বিক্রয় ট্র্যাকিং",
      description:
        language === "en"
          ? "Monitor all sales transactions with detailed reports, invoice history, and customer management"
          : "বিস্তারিত রিপোর্ট, ইনভয়েস ইতিহাস এবং গ্রাহক ব্যবস্থাপনা সহ সমস্ত বিক্রয় লেনদেন পর্যবেক্ষণ করুন",
    },
    {
      icon: "📈",
      title: language === "en" ? "Advanced Reports" : "উন্নত রিপোর্ট",
      description:
        language === "en"
          ? "Generate comprehensive reports with profit analysis, sales trends, and business insights"
          : "লাভ বিশ্লেষণ, বিক্রয় প্রবণতা এবং ব্যবসায়িক অন্তর্দৃষ্টি সহ ব্যাপক রিপোর্ট তৈরি করুন",
    },
    {
      icon: "🏢",
      title: language === "en" ? "Multi-Branch Support" : "মাল্টি-শাখা সমর্থন",
      description:
        language === "en"
          ? "Manage multiple branches with centralized control and branch-specific stock management"
          : "কেন্দ্রীয় নিয়ন্ত্রণ এবং শাখা-নির্দিষ্ট স্টক ব্যবস্থাপনা সহ একাধিক শাখা পরিচালনা করুন",
    },
    {
      icon: "👥",
      title: language === "en" ? "User Management" : "ব্যবহারকারী ব্যবস্থাপনা",
      description:
        language === "en"
          ? "Role-based access control with Owner, Manager, and Salesman permissions"
          : "মালিক, ম্যানেজার এবং বিক্রয়কর্মী অনুমতি সহ ভূমিকা-ভিত্তিক অ্যাক্সেস নিয়ন্ত্রণ",
    },
    {
      icon: "💸",
      title: language === "en" ? "Expense Tracking" : "খরচ ট্র্যাকিং",
      description:
        language === "en"
          ? "Keep track of all business expenses with categorization and detailed expense reports"
          : "শ্রেণীবিভাগ এবং বিস্তারিত ব্যয় রিপোর্ট সহ সমস্ত ব্যবসায়িক খরচ ট্র্যাক করুন",
    },
    {
      icon: "🏭",
      title:
        language === "en" ? "Supplier Management" : "সরবরাহকারী ব্যবস্থাপনা",
      description:
        language === "en"
          ? "Manage suppliers, track purchase orders, and monitor due payments efficiently"
          : "দক্ষতার সাথে সরবরাহকারী পরিচালনা করুন, ক্রয় আদেশ ট্র্যাক করুন এবং বকেয়া পেমেন্ট পর্যবেক্ষণ করুন",
    },
  ];

  const formatNumber = (num) => {
    return num.toLocaleString();
  };

  return (
    <div className="landing-container">
      {/* Animated Background Blobs */}
      <div className="landing-blob landing-blob-1"></div>
      <div className="landing-blob landing-blob-2"></div>
      <div className="landing-blob landing-blob-3"></div>

      {/* Navigation Bar */}
      <nav className="landing-nav">
        <div className="landing-nav-content">
          <div className="landing-nav-logo">
            <div className="landing-logo-icon">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2.5}
                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                />
              </svg>
            </div>
            <span className="landing-logo-text">{t("appName")}</span>
          </div>
          <div className="landing-nav-actions">
            <button onClick={toggleLanguage} className="landing-lang-btn">
              {language === "en" ? "বাংলা" : "English"}
            </button>
            <button
              onClick={() => navigate("/login")}
              className="landing-login-btn"
            >
              {language === "en" ? "Login" : "লগইন"}
            </button>
          </div>
        </div>
      </nav>

      {/* Hero Section */}
      <section className="landing-hero">
        <div className="landing-hero-content">
          <h1 className="landing-hero-title">
            {language === "en" ? (
              <>
                Manage Your Business
                <br />
                <span className="landing-hero-gradient">Smarter & Faster</span>
              </>
            ) : (
              <>
                আপনার ব্যবসা পরিচালনা করুন
                <br />
                <span className="landing-hero-gradient">স্মার্ট এবং দ্রুত</span>
              </>
            )}
          </h1>
          <p className="landing-hero-description">
            {language === "en"
              ? "Complete Point of Sale and Inventory Management System for Multi-Branch businesses. Track sales, manage stock, analyze profits, and grow your business with real-time insights."
              : "মাল্টি-শাখা ব্যবসার জন্য সম্পূর্ণ পয়েন্ট অফ সেল এবং ইনভেন্টরি ম্যানেজমেন্ট সিস্টেম। বিক্রয় ট্র্যাক করুন, স্টক পরিচালনা করুন, লাভ বিশ্লেষণ করুন এবং রিয়েল-টাইম অন্তর্দৃষ্টি দিয়ে আপনার ব্যবসা বৃদ্ধি করুন।"}
          </p>
          <div className="landing-hero-actions">
            <button
              onClick={() => navigate("/login")}
              className="landing-btn-primary"
            >
              {language === "en" ? "Get Started" : "শুরু করুন"}
              <svg
                className="landing-btn-icon"
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
            </button>
            <button
              onClick={() => {
                document
                  .getElementById("features")
                  ?.scrollIntoView({ behavior: "smooth" });
              }}
              className="landing-btn-secondary"
            >
              {language === "en" ? "Learn More" : "আরও জানুন"}
            </button>
          </div>
        </div>

        {/* Hero Image/Illustration */}
        <div className="landing-hero-visual">
          <div className="landing-visual-card landing-visual-card-1">
            <div className="landing-visual-icon">📊</div>
            <div className="landing-visual-text">
              {language === "en"
                ? "Real-time Analytics"
                : "রিয়েল-টাইম বিশ্লেষণ"}
            </div>
          </div>
          <div className="landing-visual-card landing-visual-card-2">
            <div className="landing-visual-icon">💰</div>
            <div className="landing-visual-text">
              {language === "en" ? "Profit Tracking" : "লাভ ট্র্যাকিং"}
            </div>
          </div>
          <div className="landing-visual-card landing-visual-card-3">
            <div className="landing-visual-icon">🔒</div>
            <div className="landing-visual-text">
              {language === "en"
                ? "Secure & Reliable"
                : "নিরাপদ এবং নির্ভরযোগ্য"}
            </div>
          </div>
        </div>
      </section>

      {/* Stats Section - Full Width */}
      <section className="landing-stats-section">
        <div className="landing-stats-container">
          <div className="landing-stat-item">
            <div className="landing-stat-value">
              {formatNumber(stats.totalSales)}+
            </div>
            <div className="landing-stat-label">
              {language === "en" ? "Transactions" : "লেনদেন"}
            </div>
          </div>
          <div className="landing-stat-divider"></div>
          <div className="landing-stat-item">
            <div className="landing-stat-value">
              {formatNumber(stats.totalProducts)}+
            </div>
            <div className="landing-stat-label">
              {language === "en" ? "Products" : "পণ্য"}
            </div>
          </div>
          <div className="landing-stat-divider"></div>
          <div className="landing-stat-item">
            <div className="landing-stat-value">{stats.totalBranches}</div>
            <div className="landing-stat-label">
              {language === "en" ? "Branches" : "শাখা"}
            </div>
          </div>
          <div className="landing-stat-divider"></div>
          <div className="landing-stat-item">
            <div className="landing-stat-value">
              {formatNumber(stats.totalUsers)}+
            </div>
            <div className="landing-stat-label">
              {language === "en" ? "Users" : "ব্যবহারকারী"}
            </div>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section id="features" className="landing-features">
        <div className="landing-section-header">
          <h2 className="landing-section-title">
            {language === "en" ? "Powerful Features" : "শক্তিশালী বৈশিষ্ট্য"}
          </h2>
          <p className="landing-section-subtitle">
            {language === "en"
              ? "Everything you need to manage your business efficiently"
              : "আপনার ব্যবসা দক্ষতার সাথে পরিচালনা করার জন্য আপনার প্রয়োজনীয় সবকিছু"}
          </p>
        </div>

        <div className="landing-features-grid">
          {features.map((feature, index) => (
            <div key={index} className="landing-feature-card">
              <div className="landing-feature-icon">{feature.icon}</div>
              <h3 className="landing-feature-title">{feature.title}</h3>
              <p className="landing-feature-description">
                {feature.description}
              </p>
            </div>
          ))}
        </div>
      </section>

      {/* Statistics Section */}
      <section className="landing-statistics">
        <div className="landing-statistics-content">
          <h2 className="landing-statistics-title">
            {language === "en"
              ? "Trusted by Businesses"
              : "ব্যবসায় বিশ্বাসযোগ্য"}
          </h2>
          <p className="landing-statistics-description">
            {language === "en"
              ? "Join hundreds of businesses that trust ByabshaTrack for their daily operations"
              : "শত শত ব্যবসায় যোগ দিন যারা তাদের দৈনন্দিন কার্যক্রমের জন্য ব্যবসা ট্র্যাককে বিশ্বাস করে"}
          </p>

          <div className="landing-statistics-grid">
            <div className="landing-statistics-card">
              <div className="landing-statistics-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                  />
                </svg>
              </div>
              <div className="landing-statistics-value">99.9%</div>
              <div className="landing-statistics-label">
                {language === "en" ? "Uptime" : "আপটাইম"}
              </div>
            </div>

            <div className="landing-statistics-card">
              <div className="landing-statistics-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M13 10V3L4 14h7v7l9-11h-7z"
                  />
                </svg>
              </div>
              <div className="landing-statistics-value">&lt;100ms</div>
              <div className="landing-statistics-label">
                {language === "en" ? "Response Time" : "প্রতিক্রিয়া সময়"}
              </div>
            </div>

            <div className="landing-statistics-card">
              <div className="landing-statistics-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                  />
                </svg>
              </div>
              <div className="landing-statistics-value">100%</div>
              <div className="landing-statistics-label">
                {language === "en" ? "Secure" : "নিরাপদ"}
              </div>
            </div>

            <div className="landing-statistics-card">
              <div className="landing-statistics-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2}
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                  />
                </svg>
              </div>
              <div className="landing-statistics-value">
                {formatNumber(stats.totalUsers)}+
              </div>
              <div className="landing-statistics-label">
                {language === "en" ? "Active Users" : "সক্রিয় ব্যবহারকারী"}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Call to Action */}
      <section className="landing-cta">
        <div className="landing-cta-content">
          <h2 className="landing-cta-title">
            {language === "en"
              ? "Ready to Transform Your Business?"
              : "আপনার ব্যবসা রূপান্তর করতে প্রস্তুত?"}
          </h2>
          <p className="landing-cta-description">
            {language === "en"
              ? "Start managing your business smarter today with ByabshaTrack. Try it now with our demo account."
              : "ব্যবসা ট্র্যাক দিয়ে আজই আপনার ব্যবসা স্মার্টভাবে পরিচালনা করা শুরু করুন। আমাদের ডেমো অ্যাকাউন্ট দিয়ে এখনই চেষ্টা করুন।"}
          </p>
          <button
            onClick={() => navigate("/login")}
            className="landing-cta-button"
          >
            {language === "en" ? "Get Started Free" : "বিনামূল্যে শুরু করুন"}
            <svg
              className="landing-btn-icon"
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
          </button>
        </div>
      </section>

      {/* Footer */}
      <footer className="landing-footer">
        <div className="landing-footer-content">
          <div className="landing-footer-brand">
            <div className="landing-footer-logo">
              <div className="landing-logo-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path
                    strokeLinecap="round"
                    strokeLinejoin="round"
                    strokeWidth={2.5}
                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                  />
                </svg>
              </div>
              <span className="landing-logo-text">{t("appName")}</span>
            </div>
            <p className="landing-footer-description">
              {language === "en"
                ? "Modern Point of Sale and Inventory Management System for growing businesses"
                : "ক্রমবর্ধমান ব্যবসার জন্য আধুনিক পয়েন্ট অফ সেল এবং ইনভেন্টরি ম্যানেজমেন্ট সিস্টেম"}
            </p>
          </div>

          <div className="landing-footer-links">
            <div className="landing-footer-section">
              <h4 className="landing-footer-heading">
                {language === "en" ? "Product" : "পণ্য"}
              </h4>
              <ul className="landing-footer-list">
                <li>
                  <a href="#features">
                    {language === "en" ? "Features" : "বৈশিষ্ট্য"}
                  </a>
                </li>
                <li>
                  <a href="#features">
                    {language === "en" ? "Pricing" : "মূল্য"}
                  </a>
                </li>
                <li>
                  <a href="#features">{language === "en" ? "Demo" : "ডেমো"}</a>
                </li>
              </ul>
            </div>

            <div className="landing-footer-section">
              <h4 className="landing-footer-heading">
                {language === "en" ? "Support" : "সমর্থন"}
              </h4>
              <ul className="landing-footer-list">
                <li>
                  <a href="#features">
                    {language === "en" ? "Documentation" : "ডকুমেন্টেশন"}
                  </a>
                </li>
                <li>
                  <a href="#features">
                    {language === "en" ? "Help Center" : "সাহায্য কেন্দ্র"}
                  </a>
                </li>
                <li>
                  <a href="#features">
                    {language === "en" ? "Contact" : "যোগাযোগ"}
                  </a>
                </li>
              </ul>
            </div>

            <div className="landing-footer-section">
              <h4 className="landing-footer-heading">
                {language === "en" ? "Company" : "কোম্পানি"}
              </h4>
              <ul className="landing-footer-list">
                <li>
                  <a href="#features">
                    {language === "en" ? "About" : "সম্পর্কে"}
                  </a>
                </li>
                <li>
                  <a href="#features">{language === "en" ? "Blog" : "ব্লগ"}</a>
                </li>
                <li>
                  <a href="#features">
                    {language === "en" ? "Careers" : "ক্যারিয়ার"}
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div className="landing-footer-bottom">
          <p className="landing-footer-copyright">
            © 2024 ByabshaTrack.{" "}
            {language === "en"
              ? "All rights reserved."
              : "সর্বস্বত্ব সংরক্ষিত।"}
          </p>
          <div className="landing-footer-social">
            <button className="landing-social-btn" aria-label="Facebook">
              <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
              </svg>
            </button>
            <button className="landing-social-btn" aria-label="Twitter">
              <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
              </svg>
            </button>
            <button className="landing-social-btn" aria-label="LinkedIn">
              <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
              </svg>
            </button>
          </div>
        </div>
      </footer>
    </div>
  );
};

export default Landing;

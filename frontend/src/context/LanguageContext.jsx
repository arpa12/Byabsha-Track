import React, { createContext, useState, useContext, useEffect } from "react";

const LanguageContext = createContext(null);

// Translation data
const translations = {
  en: {
    // Common
    appName: "ByabshaTrack",
    appTagline: "Multi-branch POS & Inventory Management System",
    language: "Language",
    loading: "Loading...",
    save: "Save",
    cancel: "Cancel",
    delete: "Delete",
    edit: "Edit",
    view: "View",
    search: "Search",
    filter: "Filter",
    export: "Export",
    print: "Print",
    success: "Success",
    error: "Error",
    warning: "Warning",
    info: "Info",

    // Auth
    login: "Login",
    logout: "Logout",
    register: "Register",
    email: "Email Address",
    password: "Password",
    confirmPassword: "Confirm Password",
    forgotPassword: "Forgot Password?",
    rememberMe: "Remember Me",
    signIn: "Sign in",
    signUp: "Sign up",
    signingIn: "Signing in...",
    dontHaveAccount: "Don't have an account?",
    alreadyHaveAccount: "Already have an account?",
    welcomeBack: "Welcome Back!",
    loginSubtitle: "Sign in to access your business dashboard",
    demoCredentials: "Demo Credentials",
    owner: "Owner",
    manager: "Manager",
    salesman: "Salesman",

    // Navigation
    dashboard: "Dashboard",
    pos: "Point of Sale",
    sales: "Sales",
    products: "Products",
    categories: "Categories",
    purchases: "Purchases",
    suppliers: "Suppliers",
    expenses: "Expenses",
    branches: "Branches",
    reports: "Reports",
    users: "Users",
    settings: "Settings",
    profile: "Profile",

    // Dashboard
    totalSales: "Total Sales",
    totalPurchases: "Total Purchases",
    totalExpenses: "Total Expenses",
    netProfit: "Net Profit",
    todaySales: "Today's Sales",
    monthlySales: "Monthly Sales",
    yearlySales: "Yearly Sales",
    recentTransactions: "Recent Transactions",
    topProducts: "Top Products",
    lowStock: "Low Stock Items",

    // Products
    productName: "Product Name",
    productCode: "Product Code",
    category: "Category",
    price: "Price",
    stock: "Stock",
    quantity: "Quantity",
    unit: "Unit",
    addProduct: "Add Product",
    editProduct: "Edit Product",
    deleteProduct: "Delete Product",

    // Sales
    saleDate: "Sale Date",
    customer: "Customer",
    total: "Total",
    discount: "Discount",
    tax: "Tax",
    grandTotal: "Grand Total",
    paid: "Paid",
    due: "Due",
    paymentMethod: "Payment Method",
    cash: "Cash",
    card: "Card",
    mobile: "Mobile Banking",

    // Reports
    salesReport: "Sales Report",
    purchaseReport: "Purchase Report",
    expenseReport: "Expense Report",
    profitReport: "Profit & Loss Report",
    stockReport: "Stock Report",
    dateFrom: "From Date",
    dateTo: "To Date",
    generateReport: "Generate Report",

    // Validation
    fieldRequired: "This field is required",
    invalidEmail: "Invalid email address",
    passwordMismatch: "Passwords do not match",
    minLength: "Minimum length is",
    maxLength: "Maximum length is",
  },
  bn: {
    // Common
    appName: "ব্যবসা ট্র্যাক",
    appTagline:
      "মাল্টি-ব্রাঞ্চ পয়েন্ট অফ সেল ও ইনভেন্টরি ম্যানেজমেন্ট সিস্টেম",
    language: "ভাষা",
    loading: "লোড হচ্ছে...",
    save: "সংরক্ষণ করুন",
    cancel: "বাতিল করুন",
    delete: "মুছে ফেলুন",
    edit: "সম্পাদনা করুন",
    view: "দেখুন",
    search: "অনুসন্ধান করুন",
    filter: "ফিল্টার করুন",
    export: "এক্সপোর্ট করুন",
    print: "প্রিন্ট করুন",
    success: "সফল",
    error: "ত্রুটি",
    warning: "সতর্কতা",
    info: "তথ্য",

    // Auth
    login: "লগইন",
    logout: "লগআউট",
    register: "নিবন্ধন করুন",
    email: "ইমেইল ঠিকানা",
    password: "পাসওয়ার্ড",
    confirmPassword: "পাসওয়ার্ড নিশ্চিত করুন",
    forgotPassword: "পাসওয়ার্ড ভুলে গেছেন?",
    rememberMe: "মনে রাখুন",
    signIn: "সাইন ইন করুন",
    signUp: "সাইন আপ করুন",
    signingIn: "সাইন ইন হচ্ছে...",
    dontHaveAccount: "অ্যাকাউন্ট নেই?",
    alreadyHaveAccount: "ইতিমধ্যে অ্যাকাউন্ট আছে?",
    welcomeBack: "স্বাগতম!",
    loginSubtitle: "আপনার ব্যবসার ড্যাশবোর্ড অ্যাক্সেস করতে সাইন ইন করুন",
    demoCredentials: "ডেমো তথ্য",
    owner: "মালিক",
    manager: "ম্যানেজার",
    salesman: "সেলসম্যান",

    // Navigation
    dashboard: "ড্যাশবোর্ড",
    pos: "পয়েন্ট অফ সেল",
    sales: "বিক্রয়",
    products: "পণ্য",
    categories: "বিভাগ",
    purchases: "ক্রয়",
    suppliers: "সরবরাহকারী",
    expenses: "খরচ",
    branches: "শাখা",
    reports: "রিপোর্ট",
    users: "ব্যবহারকারী",
    settings: "সেটিংস",
    profile: "প্রোফাইল",

    // Dashboard
    totalSales: "মোট বিক্রয়",
    totalPurchases: "মোট ক্রয়",
    totalExpenses: "মোট খরচ",
    netProfit: "নিট লাভ",
    todaySales: "আজকের বিক্রয়",
    monthlySales: "মাসিক বিক্রয়",
    yearlySales: "বার্ষিক বিক্রয়",
    recentTransactions: "সাম্প্রতিক লেনদেন",
    topProducts: "জনপ্রিয় পণ্য",
    lowStock: "কম মজুদ",

    // Products
    productName: "পণ্যের নাম",
    productCode: "পণ্য কোড",
    category: "বিভাগ",
    price: "মূল্য",
    stock: "মজুদ",
    quantity: "পরিমাণ",
    unit: "একক",
    addProduct: "পণ্য যোগ করুন",
    editProduct: "পণ্য সম্পাদনা করুন",
    deleteProduct: "পণ্য মুছে ফেলুন",

    // Sales
    saleDate: "বিক্রয়ের তারিখ",
    customer: "ক্রেতা",
    total: "মোট",
    discount: "ছাড়",
    tax: "কর",
    grandTotal: "সর্বমোট",
    paid: "পরিশোধিত",
    due: "বকেয়া",
    paymentMethod: "পেমেন্ট পদ্ধতি",
    cash: "নগদ",
    card: "কার্ড",
    mobile: "মোবাইল ব্যাংকিং",

    // Reports
    salesReport: "বিক্রয় রিপোর্ট",
    purchaseReport: "ক্রয় রিপোর্ট",
    expenseReport: "খরচ রিপোর্ট",
    profitReport: "লাভ ও ক্ষতি রিপোর্ট",
    stockReport: "মজুদ রিপোর্ট",
    dateFrom: "তারিখ থেকে",
    dateTo: "তারিখ পর্যন্ত",
    generateReport: "রিপোর্ট তৈরি করুন",

    // Validation
    fieldRequired: "এই ক্ষেত্রটি আবশ্যক",
    invalidEmail: "অবৈধ ইমেইল ঠিকানা",
    passwordMismatch: "পাসওয়ার্ড মিলছে না",
    minLength: "ন্যূনতম দৈর্ঘ্য হল",
    maxLength: "সর্বোচ্চ দৈর্ঘ্য হল",
  },
};

export const LanguageProvider = ({ children }) => {
  const [language, setLanguage] = useState(() => {
    // Get saved language from localStorage or default to English
    return localStorage.getItem("language") || "en";
  });

  useEffect(() => {
    // Save language preference to localStorage
    localStorage.setItem("language", language);
  }, [language]);

  const toggleLanguage = () => {
    setLanguage((prev) => (prev === "en" ? "bn" : "en"));
  };

  const t = (key) => {
    return translations[language][key] || key;
  };

  const value = {
    language,
    setLanguage,
    toggleLanguage,
    t,
    isEnglish: language === "en",
    isBangla: language === "bn",
  };

  return (
    <LanguageContext.Provider value={value}>
      {children}
    </LanguageContext.Provider>
  );
};

export const useLanguage = () => {
  const context = useContext(LanguageContext);
  if (!context) {
    throw new Error("useLanguage must be used within a LanguageProvider");
  }
  return context;
};

export default LanguageContext;

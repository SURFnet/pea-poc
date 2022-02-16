export const getToken = () => document.head.querySelector('meta[name="csrf-token"]').content;

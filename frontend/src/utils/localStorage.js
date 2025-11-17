export const getFromLocalStorage = (key, defaultValue = null, allowedValues = null) => {
  try {
    const stored = localStorage.getItem(key);
    
    if (!stored) {
      return defaultValue;
    }

    const parsed = JSON.parse(stored);

    if (allowedValues && Array.isArray(allowedValues)) {
      if (!allowedValues.includes(parsed)) {
        console.warn(`Invalid value "${parsed}" for key "${key}". Using default.`);
        return defaultValue;
      }
    }

    return parsed;
  } catch (error) {
    console.error(`Error reading from localStorage (key: ${key}):`, error);
    return defaultValue;
  }
};

export const setToLocalStorage = (key, value, allowedValues = null) => {
  try {
    if (allowedValues && Array.isArray(allowedValues)) {
      if (!allowedValues.includes(value)) {
        console.warn(`Invalid value "${value}" for key "${key}". Not saving.`);
        return false;
      }
    }

    localStorage.setItem(key, JSON.stringify(value));
    return true;
  } catch (error) {
    console.error(`Error saving to localStorage (key: ${key}):`, error);
    return false;
  }
};
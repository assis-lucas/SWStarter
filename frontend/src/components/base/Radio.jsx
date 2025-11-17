const Radio = ({ label, value, checked, onChange, name }) => {
  const id = `${name}-${value}`;

  return (
    <div className="flex items-center cursor-pointer">
      <input
        id={id}
        type="radio"
        value={value}
        checked={checked}
        onChange={onChange}
        name={name}
        className="relative size-4 appearance-none rounded-full border border-gray-300 bg-white before:absolute before:inset-1 before:rounded-full before:bg-white not-checked:before:hidden checked:border-blue-600 checked:bg-blue-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:border-gray-300 disabled:bg-gray-100 disabled:before:bg-gray-400 forced-colors:appearance-auto forced-colors:before:hidden"
      />
      <label htmlFor={id} className="ml-3 block font-bold text-gray-900 cursor-pointer">{label}</label>
    </div>
  );
};

export default Radio;

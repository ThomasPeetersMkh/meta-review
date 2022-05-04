import { createContext, useState } from "react";

const UserContext = createContext();

function UserProvider(props) {
  const [user, setUser] = useState({
    name: "ThomasMKH",
  });

  const value = {
    user,
    setUser,
  };

  return (
    <UserContext.Provider value={value}>{props.children}</UserContext.Provider>
  );
}

export { UserContext, UserProvider };

import "./css/styles.scss";
import Product from "./components/Product";
import Comment from "./components/Comment";
import { useContext } from "react";
import { UserContext, UserProvider } from "./Context/userContext";
import { useState, useEffect } from "react";
import useAxios from "./hooks/useAxios";
import Student from "./components/Student";

function App() {
  const { user } = useContext(UserContext);
  const [students, setStudents] = useState();

  useEffect(() => {
    (async () => {
      const { get } = useAxios("https://wdev2.be/fs_thomasp/api/");
      try {
        const { body } = await get("students");
        setStudents(body);
      } catch (error) {
        console.log(error);
      }
    })();
  }, []);

  return (
    <>
      <main className="container">
        <h1 className="title">Welkom {user.name}</h1>
        <div className="columns">
          <div className="column is-two-thirds">
            <h2 className="subtitle">Overzicht van studenten</h2>
            {students &&
              students.map((student) => {
                return <Student student={student} />;
              })}
          </div>
          <div className="column is-one-thirds">
            <h2>Comment van het product</h2>
            <Comment />
          </div>
        </div>
        <button className="button is-success">click me</button>
      </main>
    </>
  );
}

function AppWrapped() {
  return (
    <UserProvider>
      <App />
    </UserProvider>
  );
}

export default AppWrapped;

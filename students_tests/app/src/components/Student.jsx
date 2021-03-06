import axios from "axios";
import { useEffect } from "react";

const Student = ({ student }) => {
  const { llg_studentnr, llg_naam, llg_voornaam } = student;
  return (
    <>
      <div className="card">
        <div className="card-header">
          <div className="card-header-title">
            {llg_naam + " " + llg_voornaam}
          </div>
        </div>
        <div className="card-content">
          <div className="content">
            Lorem ipsum leo risus, porta ac consectetur ac, vestibulum at eros.
            Donec id elit non mi porta gravida at eget metus. Cum sociis natoque
            penatibus et magnis dis parturient montes, nascetur ridiculus mus.
            Cras mattis consectetur purus sit amet fermentum.
          </div>
        </div>
      </div>
    </>
  );
};

export default Student;

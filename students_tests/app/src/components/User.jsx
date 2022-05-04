import { useState, useEffect } from "react"
import axios from "axios"

const User = ()=>{
  const [people, setPeople] = useState([])
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState(false)
  const [search,setSearch] = useState({more:false,amount:20})

  useEffect(() => {
   (async () =>{
      try{
        setLoading(true)
        const {data: {results}} = await axios(`https://randomuser.me/api/?results=${search.amount}`);
        setLoading(false);
        if(search.more){
          setPeople([...people,...results])
        }else{
          setPeople(results)
        }
      }catch(error){
        console.error(error);
        setPeople([])
        setLoading(false)
        setError(true)
      }
      
    })()
  }, [search])
  
  return <>
    <form action="#" onSubmit={(e)=>{
      e.preventDefault()
        setSearch({more:false,amount:document.querySelector("#amount").value});
        document.querySelector("#amount").value =""
        }
      }>
      {!error && <input id="amount" type="text" pattern="[0-9]*" min="1" max="200" disabled={loading||error} />}
    </form>
    {!error && <button onClick={()=>setSearch({more:false,amount:28})}>Ik wil er 28 nieuwe</button>}
    {!error && <button onClick={()=>setSearch({more:true,amount:2})}>Ik wil er 2 meer</button>}
    {!error ? <h1>Hier zijn uw {people.length} aantal personen</h1> : null}
    {error && <h2>Er is een error, gelieve te herladen</h2>}
    {loading && <h3>Uw personen zijn aan het laden</h3>}
    {!error && people.length>0 && <ul>{ people.map(({name:{first,last},login:{uuid}})=>{
      return <li key={uuid}>{first + " " +last}</li>
    })}</ul>}
  </>
}

export default User;
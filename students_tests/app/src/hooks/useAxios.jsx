import axios from "axios";

const useAxios = (baseUrl) => {
  const get = async (url) => {
    const { data } = await axios(baseUrl + url);
    return data;
  };
  return { get };
};

export default useAxios;

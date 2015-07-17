class test101
{
	constructor(){
		this.restrict='E';
		this.templateUrl='Scripts/Directives/test101/test101.html';
	}
	static builder()	{
		return new test101(); 
	}
}
export default test101;
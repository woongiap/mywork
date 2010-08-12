#include<stdio.h>
#include<stdlib.h>
#include<math.h>
#define POPSIZE 1000
#define COMPNO	4
#define K	1E3
#define M	1E6
#define u	1E-6
#define n	1E-9
#define PI	3.141593
#define DES_ERROR 0.001
#define MAX_ITER 100
double writeR(int R);
double writeC(int C);
double abs(double x);
double assignR(int R);
double assignC(int C);
int mutation(int re0);
int mutation(int re1);
int mutation(int re2);
int mutation(int re3);

int main()
{
	int	i,j,filter,a[POPSIZE][6],h,k,m,c,kmax,pos,pos1,re0,re1,re2,re3,kmin,iter;
	int	gene[POPSIZE][COMPNO],select[POPSIZE][24],newchromo[POPSIZE][24],b[POPSIZE][24];
	double R[POPSIZE][2],C[POPSIZE][2],Q[POPSIZE],F[POPSIZE],x[POPSIZE],y[POPSIZE],cum[POPSIZE];
	double z[POPSIZE],eF[POPSIZE],eQ[POPSIZE],error[POPSIZE],fit[POPSIZE],fitness[POPSIZE];
	double error1,fit1,fitn,max,min;
	float Fc,Qf;

	printf("Sallen-Key filter component value selection 12 preferred value:");
	printf("\nChoose filter types by enter 1 or 2:\n");
	printf("1)Low Pass Filter\t2)High Pass Filter\n");
	scanf("%d",&filter);

	if(filter==1||filter==2)
	{	
		printf("Please enter the cutoff frequency in Hertz(Eg. 1000):\n");
		scanf("%f",&Fc);
		printf("Please enter the quality factor(Refer to reference table):\n");
		scanf("%f",&Qf);
		
		if(filter==1)
			printf("\nIter\tR0\tR1\tC0\tC1\tFc\tQf\tEmin\n");
		else
			printf("\nIter\tC0\tC1\tR0\tR1\tFc\tQf\tEmin\n");
//initialize chromosome
		for(k=0;k<POPSIZE;k++)			
			for(i=0;i<COMPNO;i++)	
				gene[k][i]=rand()%48;
		iter=0;
		do{	
			for(k=0;k<POPSIZE;k++)
			{	
				h=0;
				for(i=0;i<COMPNO;i++)
				{	
					if(((filter==1)&&(i==0))||((filter==1)&&(i==1)))
						R[k][i]=assignR(gene[k][i]);
					
					else if(((filter==1)&&(i==2))||((filter==1)&&(i==3)))
						C[k][i-2]=assignC(gene[k][i]);
					
					else if(((filter==2)&&(i==0))||((filter==2)&&(i==1)))
						C[k][i]=assignC(gene[k][i]);
						
					else if(((filter==2)&&(i==2))||((filter==2)&&(i==3)))
						R[k][i-2]=assignR(gene[k][i]);
				
					for(j=0;j<=5;j++)				//decimal to binary
					{								//a[k][j] is binary for 1 component(gene)
						a[k][j]=gene[k][i]%2;		//b[k][j] is binary for 1 chromosome or 4 components
						gene[k][i]=gene[k][i]/2;
						b[k][h]=a[k][j];
						h++;
					}	
				}

//evaluate population
				if(filter==1)
				{
					x[k]=R[k][0]*R[k][1]*C[k][0]*C[k][1];
					y[k]=sqrt(x[k]);
					F[k]=1/(2*PI*y[k]);
					eF[k]=Fc-F[k];
					eF[k]=abs(eF[k]);

					z[k]=R[k][0]*C[k][0]+R[k][1]*C[k][0];
					Q[k]=y[k]/z[k];
					eQ[k]=Qf-Q[k];
					eQ[k]=abs(eQ[k]);
				}

				else if(filter==2)
				{
					x[k]=R[k][0]*R[k][1]*C[k][0]*C[k][1];
					y[k]=sqrt(x[k]);
					F[k]=1/(2*PI*y[k]);
					eF[k]=Fc-F[k];
					eF[k]=abs(eF[k]);

					z[k]=R[k][1]*C[k][1]+R[k][1]*C[k][0];
					Q[k]=y[k]/z[k];
					eQ[k]=Qf-Q[k];
					eQ[k]=abs(eQ[k]);
				}
			}

			error1=0;
			fitn=0;
			for(k=0;k<POPSIZE;k++)							
			{												
				error[k]=0.5*((eF[k])/Fc)+0.5*((eQ[k])/Qf);
				error1=error1+error[k];
				fit[k]=1/error[k];								
				fitn=fitn+fit[k];
			}

//find smallest error
			min=error[0];
			for(k=0;k<POPSIZE;k++)
				if(min<=error[k])
					min=min;
				else
					min=error[k];

			for(k=0;k<POPSIZE;k++)			
				if(min==error[k])
					kmin=k;

//find cumulative of fitness where total cummulative fitness is 100
			fit1=0;
			for(k=0;k<POPSIZE;k++)
			{
				fitness[k]=fit[k]/fitn*100;
				fit1=fit1+fitness[k];
				cum[k]=fit1;
			}		

//find max fit chromo
			max=fitness[0];
			for(k=0;k<POPSIZE;k++)
				if(max>=fitness[k])
					max=max;
				else
					max=fitness[k];

			for(k=0;k<POPSIZE;k++)
				if(max==fitness[k])		
					kmax=k;
			
			for(h=0;h<24;h++)
			{
				select[0][h]=b[kmax][h];
				newchromo[0][h]=b[kmax][h];
			}

//selection of new parents based on roulette wheel
			for(m=1;m<POPSIZE;m++)
			{							
				c=rand()%101;			
				if((c>=0)&&(c<=cum[0]))
					for(h=0;h<24;h++)
						select[m][h]=b[0][h];
					
				else if((cum[POPSIZE-2]<c) && (c<=100))
					for(h=0;h<24;h++)
						select[m][h]=b[POPSIZE-1][h];
		
				else
					for(k=0;k<POPSIZE;k++)
						if((cum[k]<c) && (c<=cum[k+1]))
							for(h=0;h<24;h++)
								select[m][h]=b[k+1][h];
			}
			for(h=0;h<24;h++)
				newchromo[1][h]=select[1][h];

//crossover
			for(k=2;k<POPSIZE;k+=2)	
			{
				pos=rand()%23;	
				for(h=0;h<=pos;h++)
				{
					newchromo[k][h]=select[k][h];				
					newchromo[k+1][h]=select[k+1][h];
				}

				for(h=pos+1;h<24;h++)
				{
					newchromo[k][h]=select[k+1][h];
					newchromo[k+1][h]=select[k][h];
				}
			}

//mutation
			for(k=1;k<POPSIZE;k++)	
			{	
				pos1=rand()%24;
				if(newchromo[k][pos1]==0)
					newchromo[k][pos1]=1;
				else
					newchromo[k][pos1]=0;
			}

//copy back generation
			for(k=0;k<POPSIZE;k++)	
			{
				re0=0;
				for(h=0;h<6;h++)
					a[k][h]=newchromo[k][h];
				for(h=5;h>=0;h--)
					re0=re0+a[k][h]*pow(2,h);  
					if(re0<=47)
						gene[k][0]=re0;	
					else
						gene[k][0]=mutation(re0);
				
				re1=0;
				for(h=6;h<12;h++)
					a[k][h-6]=newchromo[k][h];
				for(h=5;h>=0;h--)
					re1=re1+a[k][h]*pow(2,h);
					if(re1<=47)
						gene[k][1]=re1;	
					else
						gene[k][1]=mutation(re1);
				
				re2=0;
				for(h=12;h<18;h++)
					a[k][h-12]=newchromo[k][h];
				for(h=5;h>=0;h--)
					re2=re2+a[k][h]*pow(2,h);
					if(re2<=47)
						gene[k][2]=re2;	
					else
						gene[k][2]=mutation(re2);
				re3=0;
				for(h=18;h<24;h++)
					a[k][h-18]=newchromo[k][h];
				for(h=5;h>=0;h--)
					re3=re3+a[k][h]*pow(2,h);
					if(re3<=47)
						gene[k][3]=re3;	
					else
						gene[k][3]=mutation(re3);
				
			}
			
			printf("Iter%d\t",iter);

			
			if(filter==1)
			{	
				writeR(gene[0][0]);
				writeR(gene[0][1]);
				writeC(gene[0][2]);
				writeC(gene[0][3]);
			}
			else
			{
				writeC(gene[0][0]);
				writeC(gene[0][1]);
				writeR(gene[0][2]);
				writeR(gene[0][3]);
			}

			printf("%f %f %f\n",F[kmax],Q[kmax],min);
			
			iter++;
		}while((min>DES_ERROR)&&(iter<=MAX_ITER));
	}
	
	else
	{
		printf("Invalid filter types\nEnter 1 or 2 only\n");
		goto end;
	}

	end:

	return 0;
}


int mutation(int x)
{	
	int	j,a1[6],p,y;

	do{
		for(j=0;j<=5;j++)	//dec to binary
		{
			a1[j]=x%2;
			x=x/2;
		}
		p=rand()%6;
		if(a1[p]==0)
			a1[p]=1;
		else
			a1[p]=0;
		y=0;				//binary to dec
		for(j=5;j>=0;j--)
			y=y+a1[j]*pow(2,j);
	}while(y>47);
	return y;

}

double abs(double x)
{
	double	z;
	z=x*x;
	z=sqrt(z);

	return	z;

}
double writeR(int R)
{  double	r;
				if(R==0)        
					{printf("R1K\t");	//1
					r=1*K; }
				else if(R==1)   
					{printf("R10K\t");
					r=10*K; }
				else if(R==2)   
					{printf("R100K\t");
					r=100*K; }
				else if(R==3)    
					{printf("R1M\t");
					r=1*M; }
				else if(R==4)    
					{printf("R1.2K\t");	//1.2
					r=1.2*K; }
				else if(R==5)    
					{printf("R12K\t");
					r=12*K; }
				else if(R==6)   
					{printf("R120K\t");
					r=120*K; }
				else if(R==7)   
					{printf("R1.2M\t");
					r=1.2*M; }
				else if(R==8)   
					{printf("R1.5K\t");	//1.5
					r=1.5*K; }
				else if(R==9)   
					{printf("R15K\t");
					r=15*K; }
				else if(R==10)   
					{printf("R150K\t");
					r=150*K; }
				else if(R==11)   
					{printf("R1.5M\t");
					r=1.5*M; }
				else if(R==12)    
					{printf("R1.8K\t");	//1.8
					r=1.8*K; }
				else if(R==13)    
					{printf("R18K\t");
					r=18*K; }
				else if(R==14)   
					{printf("R180K\t");
					r=180*K; }
				else if(R==15)   
					{printf("R1.8M\t");
					r=1.8*M; }
				else if(R==16)    
					{printf("R2.2K\t");	//2.2
					r=2.2*K; }
				else if(R==17)   
					{printf("R22K\t");
					r=22*K; }
				else if(R==18)    
					{printf("R220K\t");
					r=220*K; }
				else if(R==19)    
					{printf("R2.2M\t");
					r=2.2*M; }
				else if(R==20)    
					{printf("R2.7K\t");	//2.7
					r=2.7*K; }
				else if(R==21)   
					{printf("R27K\t");
					r=27*K; }
				else if(R==22)  
					{printf("R270K\t");
					r=270*K; }
				else if(R==23)   
					{printf("R2.7M\t");
					r=2.7*M; }
				else if(R==24)    
					{printf("R3.3K\t");		//3.3
					r=3.3*K; }
				else if(R==25)   
					{printf("R33K\t");
					r=33*K; }
				else if(R==26)   
					{printf("R330K\t");
					r=330*K; }
				else if(R==27)   
					{printf("R3.3M\t");
					r=3.3*M; }
				else if(R==28)   
					{printf("R3.9K\t");	//3.9
					r=3.9*K; }
				else if(R==29)    
					{printf("R39K\t");
					r=39*K; }
				else if(R==30)    
					{printf("R390K\t");
					r=390*K; }
				else if(R==31)   
					{printf("R3.9M\t");
					r=3.9*M; }
				else if(R==32)   
					{printf("R4.7K\t");	//4.7
					r=4.7*K; }
				else if(R==33)    
					{printf("R47K\t");
					r=47*K; }
				else if(R==34)   
					{printf("R470K\t");
					r=470*K; }
				else if(R==35)   
					{printf("R4.7M\t");
					r=4.7*M; }
				else if(R==36)   
					{printf("R5.6K\t");	//5.6
					r=5.6*K; }
				else if(R==37)   
					{printf("R56K\t");
					r=56*K; }
				else if(R==38)   
					{printf("R560K\t");
					r=560*K; }
				else if(R==39)    
					{printf("R5.6M\t");
					r=5.6*M; }
				else if(R==40)   
					{printf("R6.8K\t");	//6.8
					r=6.8*K; }
				else if(R==41)    
					{printf("R68K\t");
					r=68*K; }
				else if(R==42)    
					{printf("R680K\t");
					r=680*K; }
				else if(R==43)   
					{printf("R6.8M\t");
					r=6.8*M; }
				else if(R==44)   
					{printf("R8.2K\t");	//8.2
					r=8.2*K; }
				else if(R==45)   
					{printf("R82K\t");
					r=82*K; }
				else if(R==46)    
					{printf("R820K\t");
					r=820*K; }
				else if(R==47)    
					{printf("R8.2M\t");
					 r=8.2*M; }
				return r;
				}

double writeC(int C)
{  double	c;
				if(C==0)         
				{	printf("C1n\t");	//1
					c=1*n;}
				else if(C==1)    
				{	printf("C10n\t");
					c=10*n;}
				else if(C==2)    
				{	printf("C100n\t");
					c=100*n;}
				else if(C==3)    
				{	printf("C1u\t");
					c=1*u;}
				else if(C==4)   
				{	printf("C1.2n\t");	//1.2
					c=1.2*n;}
				else if(C==5)  
				{	printf("C12n\t");
					c=12*n;}
				else if(C==6)    
				{	printf("C120n\t");
					c=120*n;}
				else if(C==7)    
				{	printf("C1.2u\t");
					c=1.2*u;}
				else if(C==8)   
				{	printf("C1.5n\t");	//1.5
					c=1.5*n;}
				else if(C==9)    
				{	printf("C15n\t");
					c=15*n;}
				else if(C==10)    
				{	printf("C150n\t");
					c=150*n;}
				else if(C==11)    
				{	printf("C1.5u\t");
					c=1.5*u;}
				else if(C==12)   
				{	printf("C1.8n\t");	//1.8
					c=1.8*n;}
				else if(C==13)    
				{	printf("C18n\t");
					c=18*n;}
				else if(C==14)    
				{	printf("C180n\t");
					c=180*n;}
				else if(C==15)    
				{	printf("C1.8u\t");
					c=1.8*u;}
				else if(C==16)   
				{	printf("C2.2n\t");	//2.2
					c=2.2*n;}
				else if(C==17)    
				{	printf("C22n\t");
					c=22*n;}
				else if(C==18)    
				{	printf("C220n\t");
					c=220*n;}
				else if(C==19)    
				{	printf("C2.2u\t");
					c=2.2*u;}
				else if(C==20)    
				{	printf("C2.7n\t");	//2.7
					c=2.7*n;}
				else if(C==21)    
				{	printf("C27n\t");
					c=27*n;}
				else if(C==22)    
				{	printf("C270n\t");
					c=270*n;}
				else if(C==23)    
				{	printf("C2.7u\t");
					c=2.7*u;}
				else if(C==24)    
				{	printf("C3.3n\t");	//3.3
					c=3.3*n;}
				else if(C==25)    
				{	printf("C33n\t");
					c=33*n;}
				else if(C==26)    
				{	printf("C330n\t");
					 c=(330*n);}
				else if(C==27)    
				{	printf("C3.3u\t");
					c=3.3*u;}
				else if(C==28)    
				{	printf("C3.9n\t");	//3.9
					c=3.9*n;}
				else if(C==29)  
				{	printf("C39n\t");
					c=39*n;}
				else if(C==30)   
				{	printf("C390n\t");
					c=390*n;}
				else if(C==31)   
				{	printf("C3.9u\t");
					c=3.9*u;}
				else if(C==32)   
				{	printf("C4.7n\t");	//4.7
					c=4.7*n;}
				else if(C==33)   
				{	printf("C47n\t");
					c=47*n;}
				else if(C==34)    
				{	printf("C470n\t");
					c=470*n;}
				else if(C==35)    
				{	printf("C4.7u\t");
					c=4.7*u;}
				else if(C==36)    
				{	printf("C5.6n\t");	//5.6
					c=5.6*n;}
				else if(C==37)    
				{	printf("C56n\t");
					c=56*n;}
				else if(C==38)    
				{	printf("C560n\t");
					c=560*n;}
				else if(C==39)    
				{	printf("C5.6u\t");
					c=5.6*u;}
				else if(C==40)    
				{	printf("C6.8n\t");	//6.8
					c=6.8*n;}
				else if(C==41)    
				{	printf("C68n\t");
					c=68*n;}
				else if(C==42)    
				{	printf("C680n\t");
					c=680*n;}
				else if(C==43)   
				{	printf("C6.8u\t");
					c=6.8*u;}
				else if(C==44)   
				{	printf("C8.2n\t");	//8.2
					c=8.2*n;}
				else if(C==45)    
				{	printf("C82n\t");
					c=82*n;}
				else if(C==46)    
				{	printf("C820n\t");
					c=820*n;}
				else if(C==47)    
				{	printf("C8.2u\t");
					c=8.2*u;}
				return c;
			}

double assignC(int C)
{  double	c;
				if(C==0)			//1
					c=1*n;
				else if(C==1)    
					c=10*n;
				else if(C==2)    
					c=100*n;
				else if(C==3)    
					c=1*u;
				else if(C==4)   	//1.2
					c=1.2*n;
				else if(C==5)  
					c=12*n;
				else if(C==6)    
					c=120*n;
				else if(C==7)    
					c=1.2*u;
				else if(C==8)   //1.5
					c=1.5*n;
				else if(C==9)    
					c=15*n;
				else if(C==10)    
					c=150*n;
				else if(C==11)    
					c=1.5*u;
				else if(C==12)   	//1.8
					c=1.8*n;
				else if(C==13)    
					c=18*n;
				else if(C==14)    
					c=180*n;
				else if(C==15)    
					c=1.8*u;
				else if(C==16)   	//2.2
					c=2.2*n;
				else if(C==17)    
					c=22*n;
				else if(C==18)    
					c=220*n;
				else if(C==19)    
					c=2.2*u;
				else if(C==20)    	//2.7
					c=2.7*n;
				else if(C==21)    
					c=27*n;
				else if(C==22)    
					c=270*n;
				else if(C==23)    
					c=2.7*u;
				else if(C==24)    //3.3
					c=3.3*n;
				else if(C==25)    
					c=33*n;
				else if(C==26)    
					 c=330*n;
				else if(C==27)    
					c=3.3*u;
				else if(C==28)    	//3.9
					c=3.9*n;
				else if(C==29)  
					c=39*n;
				else if(C==30)   
					c=390*n;
				else if(C==31)   
					c=3.9*u;
				else if(C==32)   	//4.7
					c=4.7*n;
				else if(C==33)   
					c=47*n;
				else if(C==34)    
					c=470*n;
				else if(C==35)    
					c=4.7*u;
				else if(C==36)    	//5.6
					c=5.6*n;
				else if(C==37)    
					c=56*n;
				else if(C==38)    
					c=560*n;
				else if(C==39)    
					c=5.6*u;
				else if(C==40)    	//6.8
					c=6.8*n;
				else if(C==41)    
					c=68*n;
				else if(C==42)    
					c=680*n;
				else if(C==43)   
					c=6.8*u;
				else if(C==44)   	//8.2
					c=8.2*n;
				else if(C==45)    
					c=82*n;
				else if(C==46)    
					c=820*n;
				else if(C==47)    
					c=8.2*u;
				return c;
			}

double assignR(int R)
{  double	r;
				if(R==0)        	//1
					r=1*K; 
				else if(R==1)   
					r=10*K; 
				else if(R==2)   
					r=100*K; 
				else if(R==3)    
					r=1*M; 
				else if(R==4)    //1.2
					r=1.2*K; 
				else if(R==5)    
					r=12*K; 
				else if(R==6)   
					r=120*K; 
				else if(R==7)   
					r=1.2*M; 
				else if(R==8)   	//1.5
					r=1.5*K; 
				else if(R==9)   
					r=15*K; 
				else if(R==10)   
					r=150*K; 
				else if(R==11)   
					r=1.5*M; 
				else if(R==12)    //1.8
					r=1.8*K; 
				else if(R==13)    
					r=18*K; 
				else if(R==14)   
					r=180*K; 
				else if(R==15)   
					r=1.8*M; 
				else if(R==16)    //2.2
					r=2.2*K; 
				else if(R==17)   
					r=22*K; 
				else if(R==18)    
					r=220*K; 
				else if(R==19)    
					r=2.2*M; 
				else if(R==20)    //2.7
					r=2.7*K; 
				else if(R==21)   
					r=27*K; 
				else if(R==22)  
					r=270*K; 
				else if(R==23)   
					r=2.7*M; 
				else if(R==24)    //3.3
					r=3.3*K; 
				else if(R==25)   
					r=33*K; 
				else if(R==26)   
					r=330*K; 
				else if(R==27)   
					r=3.3*M; 
				else if(R==28)   //3.9
					r=3.9*K; 
				else if(R==29)    
					r=39*K; 
				else if(R==30)    
					r=390*K; 
				else if(R==31)   
					r=3.9*M; 
				else if(R==32)   //4.7
					r=4.7*K; 
				else if(R==33)    
					r=47*K; 
				else if(R==34)   
					r=470*K; 
				else if(R==35)   
					r=4.7*M; 
				else if(R==36) //5.6  
					r=5.6*K; 
				else if(R==37)   
					r=56*K; 
				else if(R==38)   
					r=560*K; 
				else if(R==39) 
					r=5.6*M; 
				else if(R==40)		//6.8
					r=6.8*K; 
				else if(R==41)    
					r=68*K; 
				else if(R==42)    
					r=680*K; 
				else if(R==43)   
					r=6.8*M; 
				else if(R==44)   //8.2
					r=8.2*K; 
				else if(R==45)   
					r=82*K; 
				else if(R==46)    
					r=820*K; 
				else if(R==47)    
					 r=8.2*M; 
				return r;
				}
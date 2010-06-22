package com.primed.sde.command;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.DataInputStream;
import java.io.File;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.io.OutputStreamWriter;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map.Entry;
import java.util.Set;

import org.tmatesoft.svn.core.SVNDepth;
import org.tmatesoft.svn.core.SVNException;
import org.tmatesoft.svn.core.SVNURL;
import org.tmatesoft.svn.core.wc.SVNRevision;
import org.tmatesoft.svn.core.wc.SVNUpdateClient;

/**
 * Reads and exports the content of diff.patch. It will only export the 'added' and 
 * 'modified' files, ignoring 'delete' commands (which you should deal with later in
 * the deployment process)
 * 
 * @author philip gloyne (philip.gloyne@gmail.com)
 * @since 25-JAN-2010
 */
public class Export {

	protected static String NEW_LINE = System.getProperty("line.separator");

	private final SVNUpdateClient client;
	private final File diff;
	private final String oldBranch;
	private final String newBranch;
	private final String target;
        private final HashMap<String, String> targetPatterns;
	
	/**
	 * Reads and exports the content of diff.patch.
	 * 
	 * @param client
	 * @param diff the diff.patch
	 * @param oldBranch the older branch (should be the same at the current baseline export).
	 * @param newBranch the new branch which you wish you take the baseline to.
	 * @param target the directory to output the exports
	 * @throws SVNException
	 * @throws IOException
	 */
	public Export(SVNUpdateClient client, File diff, String oldBranch, String newBranch, String target) throws SVNException, IOException {
		this.client = client;
		this.diff = diff;
		this.oldBranch = oldBranch;
		this.newBranch = newBranch;
		this.target = target;
                this.targetPatterns = new HashMap<String, String>();
                this.loadPatterns();
	}
	
	/**
	 * Read and exports all added and modified files.
	 * 
	 * @throws SVNException
	 * @throws IOException
	 * @throws InterruptedException
	 */
	public void execute() throws SVNException, IOException, InterruptedException {
		
		InputStream is = new FileInputStream(diff);
		InputStreamReader isr = new InputStreamReader(is);
		BufferedReader buf = new BufferedReader(isr);
                File parentFolder = new File(target);
                parentFolder.mkdirs();
                File outputFile = new File(target, "manifest.mf");
                OutputStream os = new FileOutputStream(outputFile);
                OutputStreamWriter osr = new OutputStreamWriter(os);
                BufferedWriter bufw = new BufferedWriter(osr);              

		String line;
		while ((line = buf.readLine()) != null) {
			bufw.write(export(line));
                        bufw.newLine();
		}
		
		buf.close();
		isr.close();
		is.close();
                bufw.close();
                osr.close();
                os.close();
	}
	
	/**
	 * Called for each line in the diff.patch. Exports a single file to the target.
	 * 
	 * @param change
	 * @throws IOException
	 * @throws InterruptedException
	 * @throws SVNException
	 */
	private String export(String change) throws IOException, InterruptedException, SVNException {
                String manifest = "";
		String operation = change.trim().charAt(0) + "";
		String path = change.trim().substring(1).trim();
		SVNURL location = SVNURL.parseURIEncoded(path.replace(oldBranch, newBranch));
                String exportTo = this.getMappedTarget(path);
                manifest += operation + " " + exportTo.replaceFirst(target, "");
		File f = new File(exportTo);
		File d = new File(f.getAbsolutePath().replaceFirst(f.getName(), ""));
		d.mkdirs();

		if (operation.equalsIgnoreCase("D")) {
			// Handle deletes if you wish, be careful of directories. 
			
		} else if (operation.equalsIgnoreCase("M") || operation.equalsIgnoreCase("A")) {
			client.doExport(location,f,SVNRevision.HEAD,SVNRevision.HEAD,"native",true,SVNDepth.EMPTY );		

		} else {
			throw new IOException("Error! Malformed operation: " + operation);
		}

                return manifest;

	}



        private void loadPatterns(){
            File file = new File("targets");
            FileInputStream fis = null;
            BufferedInputStream bis = null;
            DataInputStream dis = null;
            String line = null;
            try{
                fis = new FileInputStream(file);
                bis = new BufferedInputStream(fis);
                dis = new DataInputStream(bis);
                while (dis.available() != 0) {
                    line = dis.readLine();
                    String comps[] = line.split("=");
                    if(comps.length == 2){
                        this.targetPatterns.put(comps[0].trim(), comps[1].trim());
                    }
                }

                // dispose all the resources after using them.
                fis.close();
                bis.close();
                dis.close();
            } catch (FileNotFoundException e) {
                e.printStackTrace();
            } catch (IOException e) {
                e.printStackTrace();
            }
        }

    private String getMappedTarget(String path) {
        //First, verify if this path matches any of the patterns
        Set<Entry<String, String>> entries =  this.targetPatterns.entrySet();
        Iterator<Entry<String, String>> it = entries.iterator();
        String prefix = path.replaceFirst(oldBranch, "");

        while(it.hasNext()){
            Entry<String, String> e = it.next();
            String p = e.getKey();
            if(prefix.startsWith(p)){
                //Create new target
                String appendix = prefix.replaceFirst(p, e.getValue());
                return target + appendix;
            }
        }

        return target + prefix;
    }

}

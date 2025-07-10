<?php 
                     include "model/conn.php";

                



                                $records_per_page = 10;
                                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                $offset = ($page - 1) * $records_per_page;
                                
                                $total_records_sql = "SELECT COUNT(*) as count FROM profile_info";
                                $total_records_result = $conn->query($total_records_sql);
                                $total_records = $total_records_result->fetch_assoc()['count'];
                                $total_pages = ceil($total_records / $records_per_page);
                                 //get the account data on he database

                                 
                                $sql = "SELECT a.id, a.employee_id, a.username, a.account_type, a.date_added, 
                                       p.first_name, p.middle_initial, p.last_name, p.email 
                                        FROM accounts a 
                                        LEFT JOIN profile_info p ON a.id = p.account_id 
                                        LIMIT $offset, $records_per_page";
                                $query = $conn->query($sql);
                                while($row = $query->fetch_assoc()){
                                    
                                   echo "<tr>
                                    <td><p class='text-sm font-weight-bold mb-0 text-center ' > {$row['id']} </p></td>
                                   <td><p class='text-sm font-weight-bold mb-0 text-center ' > {$row['username']}</p></td>
                                   <td><p class='text-sm font-weight-bold mb-0 text-center ' >{$row['first_name']} {$row['middle_initial']} {$row['last_name']}</p></td>
                                   <td><p class='text-sm font-weight-bold mb-0 text-center ' >{$row['email']} </p></td>
                                   <td><p class='text-sm font-weight-bold mb-0 text-center ' >{$row['date_added']} </p></td>

                                   <td class='align-middle'>
                <div class='ms-auto'>
                    <a href='javascript:void(0)' onclick='editEmployee(\"{$row['id']}\", \"{$row['username']}\", \"{$row['email']}\", \"{$row['employee_id']}\", \"{$row['account_type']}\")' class='btn btn-link text-dark px-3 mb-0'>
                        <i class='fas fa-pencil-alt text-dark me-2'></i>Edit
                    </a>
                    <a href='javascript:void(0)' onclick='deleteEmployee({$row['id']})' class='btn btn-link text-danger px-3 mb-0'>
                        <i class='far fa-trash-alt me-2'></i>Delete
                    </a>
                </div>
            </td>
                                   </tr>
                                   
                                   
                                   
                                   
                                   
                                   ";
                                }

                                /**
                                 * 
                                 * "<tr>
                                 *     <td class='align-middle text-center'>
                                *            <div class='d-flex px-2 py-1'>
                                 *               <div class='text-center d-flex flex-column justify-content-center'>
                                  
                                 *<h6 class='text-center mb-0 text-sm'>{$row['id']}</h6>
                                  *              </div>
                                      *       </div>
                                      *   </td>
                                      *   <td class='align-middle text-center'>
                                      *       <p class='text-center text-xs font-weight-bold mb-0'>{$row['username']}</p>
                                      *   </td>
                                      *   <td class='align-middle text-center'>
                                      * *       <p class='text-center text-xs font-weight-bold mb-0'>{$row['firstname']} {$row['lastname']}</p>
                                      *   </td>
                                      *   <td class='align-middle text-center'>
                                      *       <p class='text-center text-xs font-weight-bold mb-0'>{$row['email']}</p>
                                      *   </td>
                                       *  <td class='align-middle text-center'>
                                       *      <span class='text-center text-secondary text-xs font-weight-bold'>{$row['date_added']}</span>
                                       *  </td>
                                       *  <td class='align-middle text-center'>
                                       *      <div class='dropdown'>
                                       * *          <button class='btn btn-link text-secondary mb-0' type='button' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                       *              <span class='material-symbols-rounded'>more_vert</span>
                                       *          </button>
 *<ul class='dropdown-menu'>
                                       *              <li><a class='dropdown-item text-secondary font-weight-normal text-xs' data-bs-toggle='modal' data-bs-target='#addAdminModal'>Edit</a></li>
                                       *              <li><a class='dropdown-item text-secondary font-weight-normal text-xs' href='#'>Delete</a></li>
                                       *          </ul>
                                       *      </div>
                                       *  </td>
                                   *  </tr>";
                                 */
                                ?>